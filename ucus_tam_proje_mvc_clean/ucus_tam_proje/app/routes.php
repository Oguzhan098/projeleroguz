<?php

global $pdo;
$rawPath   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path      = $rawPath;

if ($scriptDir !== '' && strpos($path, $scriptDir) === 0) {
    $path = substr($path, strlen($scriptDir));
}

if (strpos($path, '/index.php') === 0) {
    $path = substr($path, strlen('/index.php'));
}

if (isset($_GET['r']) && is_string($_GET['r']) && $_GET['r'] !== '') {
    $path = $_GET['r'];
}
if ($path === '' || $path === '/') {
    $path = '/';
}

require_once __DIR__ . '/services/FlightService.php';
require_once __DIR__ . '/services/AirportService.php';
require_once __DIR__ . '/services/PlaneService.php';
require_once __DIR__ . '/services/PersonService.php';

$flightService = new FlightService($pdo);
$airportService = new AirportService($pdo);
$planeService   = new PlaneService($pdo);
$personService  = new PersonService($pdo);

$method = $_SERVER['REQUEST_METHOD'];

switch (true) {

    case $path === '/' && $method === 'GET':
        view('flights/index', ['flights' => $flightService->all()]);
        break;

    case $path === '/flights/new' && $method === 'GET':
        view('flights/new', [
            'airports' => $airportService->all(),
            'planes'   => $planeService->all(),
            'people'   => $personService->all(),
        ]);
        break;

    case $path === '/flights' && $method === 'POST':
        $flightService->create($_POST);
        $_SESSION['flash'] = 'Uçuş eklendi.';
        redirect(url('/'));
        break;

    case preg_match('#^/flights/(\d+)/delete$#', $path, $m) && $method === 'POST':
        $flightService->delete((int)$m[1]);
        $_SESSION['flash'] = 'Uçuş silindi.';
        redirect(url('/'));
        break;

    case $path === '/airports' && $method === 'GET':
        $list = $airportService->all();
        view('airports/index', ['airports' => is_array($list) ? $list : []]);
        break;

    case $path === '/airports/new' && $method === 'GET':
        view('airports/new');
        break;

    case $path === '/airports' && $method === 'POST':
        $airportService->create($_POST);
        $_SESSION['flash'] = 'Havalimanı eklendi.';
        redirect(url('/airports'));
        break;

    case $path === '/planes' && $method === 'GET':
        $list = $planeService->all();
        view('planes/index', ['planes' => is_array($list) ? $list : []]);
        break;

    case $path === '/planes/new' && $method === 'GET':
        view('planes/new');
        break;

    case $path === '/planes' && $method === 'POST':
        $planeService->create($_POST);
        $_SESSION['flash'] = 'Uçak eklendi.';
        redirect(url('/planes'));
        break;

    case $path === '/people' && $method === 'GET':
        $list = $personService->all();
        view('people/index', ['people' => is_array($list) ? $list : []]);
        break;

    case $path === '/people/new' && $method === 'GET':
        view('people/new');
        break;

    case $path === '/people' && $method === 'POST':
        $personService->create($_POST);
        $_SESSION['flash'] = 'Kişi eklendi.';
        redirect(url('/people'));
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
}
