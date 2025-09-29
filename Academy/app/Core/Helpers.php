<?php
namespace App\Core;

class Helpers
{
    public static function e(?string $s): string
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
