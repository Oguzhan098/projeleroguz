
<?php
global $pdo;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/FlightController.php';

$controller = new FlightController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->add($_POST);
}

$controller->index();
