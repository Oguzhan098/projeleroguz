<?php
require_once '../config/config.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        (new TweetController)->home();
        break;
    case '/login':
        (new AuthController)->login();
        break;
    case '/register':
        (new AuthController)->register();
        break;
    case '/logout':
        (new AuthController)->logout();
        break;
    default:
        echo "404 Not Found";
}