<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;

$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }

$router = new Router();
$router->setBasePath($basePath);

$reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if ($reqPath === ($basePath . '/__ping') || ($basePath === '' && $reqPath === '/__ping')) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "OK PHP: " . PHP_VERSION . " BASE: " . ($basePath === '' ? '/' : $basePath);
    exit;
}

$routeParam = $_GET['r'] ?? $_GET['route'] ?? $_GET['_url'] ?? null;
if (is_string($routeParam) && $routeParam !== '') {
    $uri = parse_url($routeParam, PHP_URL_PATH) ?? '/';
} else {
    $uri = $reqPath;

    $rel = $uri;
    if ($basePath !== '' && strncmp($rel, $basePath, strlen($basePath)) === 0) {
        $rel = (string)substr($rel, strlen($basePath));
    }
    if ($rel === '' || $rel === '/' || $rel === '/index.php') {
        $uri = $basePath . '/'; // Router basePath'i düşecek ve "/" kalacak
    }
}

$router->get('/',                 'FlightsController@index');
$router->get('/flights',          'FlightsController@index');
$router->get('/flights/create',   'FlightsController@create');
$router->post('/flights',         'FlightsController@store');
$router->get('/flights/{id}/edit','FlightsController@edit');
$router->post('/flights/{id}',    'FlightsController@update');

$router->get('/flight-person/attach','FlightPersonController@attach'); // opsiyonel

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($method, $uri);
