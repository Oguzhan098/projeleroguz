<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'tweetapp');
define('DB_USER', 'root');
define('DB_PASS', 'root');

spl_autoload_register(function ($class) {
    $paths = ['core', 'models', 'controllers'];
    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . '/' . $class . '.php';
        if (file_exists($file)) require $file;
    }
});