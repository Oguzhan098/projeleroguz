<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Basit ama yeterli session ayarı
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function check(): bool
    {
        return !empty($_SESSION['auth_user']);
    }

    public static function user(): ?string
    {
        return $_SESSION['auth_user'] ?? null;
    }

    public static function attempt(string $id, string $password): bool
    {
        // Sabit kullanıcı: oguzhan / tanrıverdi
        $okId  = hash_equals('oguzhan', mb_strtolower(trim($id), 'UTF-8'));
        $okPwd = hash_equals('tanrıverdi', mb_strtolower(trim($password), 'UTF-8'));
        if ($okId && $okPwd) {
            $_SESSION['auth_user'] = 'oguzhan';
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', '', !empty($_SERVER['HTTPS']), true);
        }
        session_destroy();
    }
}
