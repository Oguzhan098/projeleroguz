<?php

namespace App\Core;


use PDO;
use PDOException;


class Database
{
    private static ?PDO $pdo = null;


    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];
            try {
                self::$pdo = new PDO($db['dsn'], $db['user'], $db['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('DB connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}