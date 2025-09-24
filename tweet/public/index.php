<?php

session_start();

// Autoload (manuel veya composer ile)
require_once '../app/core/Database.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/TweetController.php';

// Database nesnesi oluştur
$db = new Database();

// Controller'ları hazırla
$authController = new AuthController($db);
$tweetController = new TweetController($db);

// Sayfa yönlendirmesi (çok temel bir router örneği)
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'register':
        require_once '../app/views/register.php';
        break;
    case 'login':
        require_once '../app/views/login.php';
        break;
    case 'logout':
        session_destroy();
        header("Location: index.php");
        break;
    case 'tweet':
        require_once '../app/views/tweet.php';
        break;
    default:
        require_once '../app/views/home.php';
        break;
}
