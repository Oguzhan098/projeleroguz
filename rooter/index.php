<?php
require 'Router.php';

$router = new Router();

// GET istekleri
$router->get('/', function () {
    echo "Anasayfa";
});
$router->get('/customers', 'CustomerController@index');
$router->get('/customers/{id}', 'CustomerController@show');

// POST istekleri
$router->post('/customers', 'CustomerController@store');

// Yönlendirme işlemi
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$router->dispatch($method, $uri);
