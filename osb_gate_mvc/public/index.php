<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Core\Container;

// 1) ALT KLASÖR UYUMU: /osb_gate_mvc/public
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }

// 2) Ortak bilgileri tek sefer Container'a koy
$scheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$noRewrite = (($_ENV['APP_NO_REWRITE'] ?? '0') === '1') || (strpos($_SERVER['REQUEST_URI'] ?? '', 'index.php') !== false);

Container::set('app.basePath', $basePath);
Container::set('app.scheme',   $scheme);
Container::set('app.host',     $host);
Container::set('app.noRewrite',$noRewrite);

// 3) İstek URI’sini normalize et
$reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$routeParam = $_GET['r'] ?? $_GET['route'] ?? $_GET['_url'] ?? null;
if (is_string($routeParam) && $routeParam !== '') {
    $uri = parse_url($routeParam, PHP_URL_PATH) ?? '/';      // /movements
} else {
    $rel = $reqPath;                                         // /osb_gate_mvc/public/movements
    if ($basePath !== '' && strncmp($rel, $basePath, strlen($basePath)) === 0) {
        $rel = (string)substr($rel, strlen($basePath));      // /movements
    }
    $uri = ($rel === '' || $rel === '/' || $rel === '/index.php') ? '/' : $rel;
}

// Sağlık kontrolü
if ($uri === '/__ping') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "OK PHP: " . PHP_VERSION . " BASE=" . ($basePath === '' ? '/' : $basePath) . " noRewrite=" . ($noRewrite?'1':'0');
    exit;
}

// 4) Rotalar + basePath
$router = new Router();
$router->setBasePath($basePath);

$router->get('/',                 'DashboardController@index');
$router->get('/movements',        'MovementsController@index');
$router->get('/movements/create', 'MovementsController@create');
$router->post('/movements',       'MovementsController@store');
$router->get('/reports/daily',    'DashboardController@dailyReport');

// 5) Çalıştır
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($method, $uri);
