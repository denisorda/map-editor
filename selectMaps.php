<?php
require_once('connection.php');
$selectQuery = "SELECT id, name FROM levels WHERE is_deleted=0";
$levels = $link->query($selectQuery);
$levelsArr = [];
while ($level = $levels->fetch_assoc()) {
    $levelsArr[] = $level;
}
header('Content-Type: application/json');
echo json_encode($levelsArr);