<?php


require_once '../Router.php';
require_once '../HomeController.php';

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/hello/{name}', 'HomeController@hello');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
