<?php

$host = 'localhost';
$dbname = 'netology';
$dbuser = 'basova';
$dbpassword = 'basova';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", "$dbuser", "$dbpassword", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('Соединение с базой данных не установлено');
}