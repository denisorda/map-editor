<?php
require_once('connection.php');
$id = $_POST['id'];
$getLevelQuery = "SELECT * FROM levels WHERE id='$id'";
$level = $link->query($getLevelQuery)->fetch_assoc();
header('Content-Type: application/json');
echo json_encode($level);