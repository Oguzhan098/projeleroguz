<?php
namespace App\Core;

class Flash
{
    public static function set(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    public static function get(string $key): ?string
    {
        if (!empty($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}
