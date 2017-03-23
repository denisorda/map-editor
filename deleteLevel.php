<?php
require_once('connection.php');
$id = $_POST['id'];
$deleteLevelQuery = "UPDATE levels SET is_deleted=1 WHERE id=$id;";
$link->query($deleteLevelQuery);
