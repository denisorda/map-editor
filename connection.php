<?php
$host = '127.0.0.1'; // адрес сервера
$database = 'game'; // имя базы данных
$user = 'admin'; // имя пользователя
$password = 'admin'; // пароль

try {
    $link = new mysqli($host, $user, $password, $database);
    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

} catch (Exception $e) {
    echo $e->getMessage();
}




//$host = '127.0.0.1'; // адрес сервера
//$db = 'game'; // имя базы данных
//$user = 'admin'; // имя пользователя
//$pass = 'admin'; // пароль
//$charset = 'utf8';
//
//$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//$opt = [
//    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//    PDO::ATTR_EMULATE_PREPARES   => false,
//];
//$pdo = new PDO($dsn, $user, $pass, $opt);