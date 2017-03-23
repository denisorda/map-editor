<?php
require_once('connection.php');
$levelId = $_POST['levelId'];
$level = $_POST['level'];
if (!empty($levelId)){
    $editLevelQuery = "UPDATE levels SET level='$level' WHERE id=$levelId;";
    $link->query($editLevelQuery);
} else {
    $name = $_POST['name'];
    if (!empty($level)){
        $addLevelQuery = "INSERT INTO levels (`name`, `level`, `is_deleted`) VALUES ('$name', '$level', 0);";
        $link->query($addLevelQuery);
    }
}
