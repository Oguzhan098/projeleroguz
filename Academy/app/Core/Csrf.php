<?php
namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csrf'];
    }

    public static function check(?string $token): bool
    {
        return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$token);
    }
}
