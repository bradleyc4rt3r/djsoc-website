<?php

$target_dir = "/data/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$filename = basename($_FILES["fileToUpload"]["name"]);
$zipFile = "/data/complete/" . $filename . "/" . $filename .".zip"

// Check if audio format is allowed
$allowedExts = array('.mp3', '.wav', '.mpeg', '.wav', '.flac', '.ogg', 'm4a', 'wma');
if(isset($_POST["submit"])) {
  if(in_array($filetype, $allowedExts)) {
    echo "Audio format is correct - " . $fileType . ".";
    $returnCode = 0;
    
    } else {
        echo "File format is not allowed.";
        $returnCode = 1;
    }
}

public function prepEnv() {
    $split_dir = "/data/envs/" . $filename;
    if (!file_exists($split_dir)) {
        mkdir($split_dir);
        } 
    }
    try {
        if (!move_uploaded_file($target_file, $split_dir)) {
            throw new Exception('could not move file: ' . $target_file);
            $returnCode = 1;
        }        
        echo "Upload Complete!";
        $returnCode = 0;
    } catch (Exception $e) {
        die ("File did not upload -  " . $e -> getMessage());
    }
}

public function activateSpleeter() {
    echo "Activating Spleeter, this may take awhile...";
    shell_exec("bash -i /var/www/html/activate-spleeter.sh" . $filename);
}


public function zipFiles() {
    // Get real path for our folder
    $rootPath = realpath('/data/complete/' . $filename . '/');

    // Initialize archive object
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
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
}

public function emailZip() {
    $zipName = $filename . '.zip';
    $fileNoExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $zipName);
    $zipDir = "/data/complete" . $filename;
    // get email from form input from user
    $emailRecipient = "bradleyc4rt3r@gmail.com"
    shell_exec('/var/www/html/email.sh' . ' ' . $zipName . ' ' . $zipDir . ' ' . $emailRecipient);
    echo "Please check your emails.";
}


$output_dir = "/data/complete" . $filename . "/";
if($returnCode == 0) {
    try{
        prepEnv();
        activateSpleeter();    
        if(!file_exists($output_dir)) {
            throw new Exception("Split unsuccessful: " . $filename);
        }
        echo "Split Complete!";
        emailZip();
    } catch (Exception $e) {
        die ($e -> getMessage());
    }    
} else {
    echo "An unknown problem occured...";
}

?>