<?php
declare(strict_types=1);

namespace App\Core;

final class Flash
{
    private const KEY = '_flash';

    public static function set(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            throw new \RuntimeException('Session not started');
        }
        $_SESSION[self::KEY] = ['type' => $type, 'message' => $message];
    }

    public static function get(): ?array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) return null;
        $flash = $_SESSION[self::KEY] ?? null;
        unset($_SESSION[self::KEY]);
        return $flash;
    }
}
