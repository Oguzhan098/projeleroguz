<?php
// public/index.php

global $pdo;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// DB ve controller
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/FlightController.php';

// $pdo config/database.php tarafından sağlanmalı
$controller = new FlightController($pdo);

// tek çağrı: controller tüm POST işlemlerini burada yönetecek ve sonra view render edecek
$controller->index();
