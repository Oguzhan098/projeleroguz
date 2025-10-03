<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();
$router->dispatch();
