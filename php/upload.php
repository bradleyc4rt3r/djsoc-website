<?php
$target = "upload/";
$target = $target . basename( $_FILES['uploaded']['name']) ;
$ok=1;

if ($uploaded_size > 350000) {
    echo "Your file is too large.";
    $ok=0;
}
if ($uploaded_type =="text/php") {
    echo "No PHP files";
    $ok=0;
}
if ($ok==0) {
Echo "Sorry, your file was not uploaded";
}
else {
    if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
        echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
         }
    else {
        echo "Sorry, there was a problem uploading your file.";
    }
}
?>