<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;
use App\Core\Container;
use App\Core\Auth;

// -----------------------------
// 0) SESSION
// -----------------------------
Auth::startSession();

// -----------------------------
// 1) BASE PATH (alt klasör desteği)
// -----------------------------
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }

// -----------------------------
// 2) ORTAK BİLGİLER (Url helper için)
// -----------------------------
$scheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$noRewrite = (($_ENV['APP_NO_REWRITE'] ?? '0') === '1') || (strpos($_SERVER['REQUEST_URI'] ?? '', 'index.php') !== false);

// Container -> Url helper bu bilgileri kullanıyor
Container::set('app.basePath', $basePath);
Container::set('app.scheme',   $scheme);
Container::set('app.host',     $host);
Container::set('app.noRewrite',$noRewrite);

// -----------------------------
// 3) URI NORMALİZASYONU
//    - r, route, _url parametreleri destekli
//    - mutlaka "/" ile başlat
// -----------------------------
$reqPath    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$routeParam = $_GET['r'] ?? $_GET['route'] ?? $_GET['_url'] ?? null;

if (is_string($routeParam) && $routeParam !== '') {
    $uri = parse_url($routeParam, PHP_URL_PATH) ?? '/';
    $uri = '/' . ltrim($uri, '/'); // r=movements  -> /movements
} else {
    // Örn: /osb_gate_mvc/public/movements  -> /movements
    $rel = $reqPath;
    if ($basePath !== '' && strncmp($rel, $basePath, strlen($basePath)) === 0) {
        $rel = (string)substr($rel, strlen($basePath));
    }
    $uri = ($rel === '' || $rel === '/' || $rel === '/index.php') ? '/' : $rel;
}

// -----------------------------
// 4) SAĞLIK KONTROLÜ
// -----------------------------
if ($uri === '/__ping') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "OK PHP: " . PHP_VERSION
        . " BASE=" . ($basePath === '' ? '/' : $basePath)
        . " noRewrite=" . ($noRewrite ? '1' : '0')
        . " user=" . (Auth::user() ?? '-');
    exit;
}

// -----------------------------
// 5) ROUTER ve ROTALAR
// -----------------------------
$router = new Router();
$router->setBasePath($basePath);

// PUBLIC (giriş gerektirmez)
$router->get('/login',  'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// API (token ile korunuyor; login gerektirmez)
$router->post('/api/ingest', 'ApiController@ingest');

// PROTECTED (giriş gerekli)
$router->get('/',                  'DashboardController@index');
$router->get('/movements',         'MovementsController@index');
$router->get('/movements/create',  'MovementsController@create');
$router->post('/movements',        'MovementsController@store');

// Raporlar / İstatistik
$router->get('/reports/stats',     'ReportsController@stats');
// Eski günlük rapor linki varsa yönlendirelim:
$router->get('/reports/daily',     'ReportsController@redirectDailyToStats');

// -----------------------------
// 6) BASİT ERİŞİM KONTROLÜ (GUARD)
// -----------------------------
// Statik dosyalar: /assets/...  (web sunucusu servis eder)
// Giriş yapılmadıysa; /login, /logout, /__ping, /api/ingest ve /assets/* dışındakiler login'e yönlendirilsin.
$publicAllowed =
    ($uri === '/login') ||
    ($uri === '/logout') ||
    ($uri === '/__ping') ||
    (preg_match('#^/assets/#', $uri) === 1) ||
    ($uri === '/api/ingest');

if (!$publicAllowed && !Auth::check()) {
    header('Location: ' . \App\Core\Url::to('/login'));
    exit;
}

// -----------------------------
// 7) ÇALIŞTIR
// -----------------------------
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($method, $uri);
