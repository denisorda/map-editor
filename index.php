<?php
require_once('connection.php');
if (!empty($_POST['uploading']) && $_POST['uploading']==='start') {
    require('public/jQuery-File-Upload-9.17.0/server/php/UploadHandler.php');
    $upload_handler = new UploadHandler();
} else {
    require('./main.php');
}