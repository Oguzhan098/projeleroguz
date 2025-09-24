<?php
global $pdo;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/FlightController.php';

$action = $_GET['action'] ?? 'index';
$controller = new FlightController($pdo);

if ($action === 'index') {
    $controller->index();
} elseif ($action === 'add') {
    $controller->add($_POST);
} else {
    echo "Sayfa bulunamadı.";
}
