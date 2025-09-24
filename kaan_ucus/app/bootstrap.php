<?php
declare(strict_types=1);

spl_autoload_register(function(string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) require $file;
});

use App\Core\Container;

$config = require __DIR__ . '/../config/database.php';
Container::set('config.db', $config);

date_default_timezone_set('UTC');
