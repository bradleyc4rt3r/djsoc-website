<?php
// Run:$ composer require aws/aws-sdk-php (put in env)

try {
    shell_exec("/opt/sudjsoc-spleeter/sync_from_s3.sh");
} catch (Exception $e) {
    echo "upload bucket bash script failure.";
    die("Error: " . $e->getMessage());
}

require './vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// AWS Info
$IAM_KEY = 'AKIAJW3DWFR6LSHSA2HA';
$IAM_SECRET = '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';

// Connect to AWS
try {
    $s3 = S3Client::factory(
        array(
            'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
            ),
            'version' => 'latest',
            'region'  => 'eu-west-2'
        )
    );
} catch (Exception $e) {
        die("Error: " . $e->getMessage());
}

// Read counter file for bucket session number
try {
    $counter = file_get_contents('./counter.txt', true);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$bucketName = 'split-tmp-upload-bucket-1';
$keyName = 'single_split_test' . $counter . '/' . basename($_FILES['fileToUpload']['name']);
$pathInS3 = 'https://s3.eu-west-2.amazonaws.com/' . $bucketName . '/' . $keyName;


// Add it to S3
try {
	// Uploaded:
	#require($_FILES['fileToUpload']['name']);
	$file = $_FILES['fileToUpload']['tmp_name'];
	echo $file;

    $s3->putObject(
        array(
            'Bucket'=>$bucketName,
            'Key' =>  $keyName,
	    'SourceFile' => $file,
	    'ContentType' => 'audio/mpeg',
            'StorageClass' => 'REDUCED_REDUNDANCY'
        )
    );

} catch (S3Exception $e) {
    die('Error:' . $e->getMessage());
} catch (Exception $e) {
    die('Error:' . $e->getMessage());
}

echo 'Done';

// Now that you have it working, I recommend adding some checks on the files.
// Example: Max size, allowed file types, etc.
?>

