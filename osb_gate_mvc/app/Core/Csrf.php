<?php
declare(strict_types=1);

namespace App\Core;

final class Csrf {
    public static function token(): string {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        return $_SESSION['_csrf'] ??= bin2hex(random_bytes(32));
    }
    public static function check(?string $t): bool {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        return is_string($t) && isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $t);
    }
}
