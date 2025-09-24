<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model {
    protected PDO $pdo;

    public function __construct() {
        $cfg = Container::get('config.db');
        $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $cfg['host'], $cfg['port'], $cfg['dbname']);
        $this->pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $this->pdo->exec("SET TIME ZONE 'UTC'");
    }
}
