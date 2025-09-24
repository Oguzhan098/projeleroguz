<?php
require_once __DIR__ . '/../config/database.php';

$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/' || $uri === '/flights') {
    require_once __DIR__ . '/../app/Controllers/FlightController.php';
    $controller = new FlightController($pdo);
    $controller->index();
} elseif ($uri === '/planes') {
    require_once __DIR__ . '/../app/Controllers/PlaneController.php';
    $controller = new PlaneController($pdo);
    $controller->index();
} elseif ($uri === '/airports') {
    require_once __DIR__ . '/../app/Controllers/AirportController.php';
    $controller = new AirportController($pdo);
    $controller->index();
} elseif ($uri === '/persons') {
    require_once __DIR__ . '/../app/Controllers/PersonController.php';
    $controller = new PersonController($pdo);
    $controller->index();
} else {
    http_response_code(404);
    echo "404 Not Found";
}
