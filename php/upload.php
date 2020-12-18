<?php

// Verify Upload
$allowedExts = array('mp3', 'wav', 'mpeg', 'wav', 'flac', 'ogg', 'm4a', 'wma');
if(isset($_POST['submit'])) {
    $filename = str_replace(' ', '', basename($_FILES["fileToUpload"]["name"]));
    echo $filename;
    $fileNameCmps = explode(".", $filename);
    $fileType = strtolower(end($fileNameCmps));
    $zipFile = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) .".zip";
    $split_dir = "/data/envs/" . $filename . "/";
    $output_dir = "/data/complete/" . preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . "/";

    if(in_array($fileType, $allowedExts)) {
        echo "Audio format is correct - " . $fileType . ".";
        $returnCode = 0;
    } else {
        $returnCode = 1;
        die("File format is not allowed.");
  }
} else {
    die("Not a POST request.");
}

function prepEnv($split_dir, $filename) {

    try {
        if (!is_dir("/data/envs/" . $filename . "/")) {
            shell_exec('mkdir /data/envs/' . $filename);
            shell_exec('chmod 0777 /data/envs/' . $filename);
            #mkdir("/data/envs/" . $_FILES["fileToUpload"]["name"] . "/", 0777, true);
        }
    } catch (Exception $e) {
        die ("Could not create directory: " . $split_dir);
    }


    try {
        global $returnCode;
        global $split_dir;
        echo $split_dir;
        $target_file = '/data/envs/' . $filename . "/" . $filename;
        if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $returnCode = 1;
            throw new Exception('Could not move file: ' . $filename);
        }
        echo "Upload Complete!";
        $returnCode = 0;
    } catch (Exception $e) {
        die ($e->getMessage());
    }
}

function activateSpleeter($filename) {
    echo "Activating Spleeter, this may take awhile...";
    chdir('/var/www/html/');
    $shell_command = "bash -i ./activate-spleeter.sh " . $filename;
    shell_exec($shell_command);
}


function zipFiles($filename, $zipFile) {

    $rootPath = realpath('/data/complete/' . preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . '/');
    $zip = new ZipArchive();
    $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file)
    {
        if (!$file->isDir())
        {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
}

function emailZip($filename) {
    $zipName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . '.zip';
    $zipDir = "/data/complete/" . preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
    $zipPath = $zipDir . '/' . $zipName;
    $oldZipPath = "/var/www/html/" . $zipName;

    if(!rename($oldZipPath, $zipPath)) {
        throw new Exception("Could not move zip: " . $zipName);
    }
    else {
        echo "File moved!";
    }

    //TODO:Get email from form input from user form
    $emailRecipient = "bradleyc4rt3r@gmail.com";
    shell_exec('/var/www/html/email.sh' . ' ' . $zipName . ' ' . $zipDir . ' ' . $emailRecipient);
    echo "Please check your emails.";
}

if($returnCode == 0) {
    try{
        global $filename;
        global $output_dir;
        global $zipFile;
        prepEnv($split_dir, $filename);
        activateSpleeter($filename);

        if(!file_exists($output_dir)) {
            throw new Exception("Split unsuccessful: " . $filename);
        } else {
            echo "Split Complete!";
            zipFiles($filename, $zipFile);
            emailZip($filename);
        }
    } catch (Exception $e) {
        die ($e -> getMessage());
    }
} else {
    echo "An unknown problem occurred...";
}
