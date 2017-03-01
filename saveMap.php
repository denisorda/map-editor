<?php
require_once('connection.php');
$name = $_POST['name'];
$level = $_POST['level'];
if (!empty($level)){
    $addLevelQuery = "INSERT INTO levels (`name`, `level`, `is_deleted`) VALUES ('$name', '$level', 0);";
    $link->query($addLevelQuery);
}