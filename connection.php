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

//    echo "Соединение с MySQL установлено!" . PHP_EOL;
//    echo "Информация о сервере: " . mysqli_get_host_info($link) . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage();
}