<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/core/Router.php';

spl_autoload_register(function ($class) {
    $paths = [
        APP_ROOT . '/controllers/' . $class . '.php',
        APP_ROOT . '/models/' . $class . '.php',
        APP_ROOT . '/core/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) { require_once $p; return; }
    }
});

Router::dispatch();
