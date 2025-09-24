<?php
declare(strict_types=1);

spl_autoload_register(function(string $class): void {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/';
    $len     = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative = substr($class, $len); // örn: Core\Container
    $file     = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$envPath = __DIR__ . '/../.env';
if (is_file($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $k = trim($parts[0]);
            $v = trim($parts[1]);
            putenv("$k=$v");
            $_ENV[$k] = $_SERVER[$k] = $v;
        }
    }
}

require_once __DIR__ . '/Core/Container.php';

use App\Core\Container;

if (!class_exists(\App\Core\Container::class)) {
    throw new \RuntimeException('Container sınıfı hâlâ bulunamadı. Lütfen app/Core/Container.php yolunu ve "namespace App\Core;" satırını kontrol et.');
}

$config = require __DIR__ . '/../config/database.php';
Container::set('config.db', $config);

date_default_timezone_set($_ENV['APP_TZ'] ?? 'UTC');
