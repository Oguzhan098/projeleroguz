<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$pdo = require __DIR__ . '/../app/config/database.php';

function url(string $path = ''): string {
    $s = rtrim($_SERVER['SCRIPT_NAME'], '/');
    return $s . $path;
}

function view($name, $data = []) {
    extract($data);
    require __DIR__ . "/../app/views/layout/header.php";
    require __DIR__ . "/../app/views/{$name}.php";
    require __DIR__ . "/../app/views/layout/footer.php";
}

function redirect(string $to) {
    header("Location: {$to}");
    exit;
}

require __DIR__ . '/../app/routes.php';
