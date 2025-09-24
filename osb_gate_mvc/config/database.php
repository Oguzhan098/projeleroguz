<?php
declare(strict_types=1);

return [
    'driver'   => 'pgsql',
    'host'     => $_ENV['DB_HOST']     ?? 'localhost',
    'port'     => (string)($_ENV['DB_PORT'] ?? '5432'),
    'database' => $_ENV['DB_NAME']     ?? 'osb_gate',
    'username' => $_ENV['DB_USER']     ?? 'postgres',
    'password' => $_ENV['DB_PASS']     ?? 'postgres',
];
