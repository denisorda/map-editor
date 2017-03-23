<?php
require_once('connection.php');
$id = $_POST['id'];
$id = $_POST['id'];
$getLevelQuery = "SELECT * FROM levels WHERE id='$id'";
header('Content-Type: application/json');
$level = $link->query($getLevelQuery)->fetch_assoc();
echo json_encode($level);






//$stmt = $pdo->prepare('SELECT * FROM levels WHERE id = :id');
//$level = $stmt->execute(array('id' => $id));
//echo json_encode($level);