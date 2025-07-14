<?php

$host = "localhost";
$db = "oguz";
$user = "postgres";
$pass = "postgres";

try {
    $pdo = new PDO("psql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
