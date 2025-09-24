<?php
declare(strict_types=1);

namespace App\Core;

final class Url
{
    public static function basePath(): string {
        return (string) Container::get('app.basePath', '');
    }

    public static function baseUrl(): string {
        $scheme = (string) Container::get('app.scheme', 'http');
        $host   = (string) Container::get('app.host', 'localhost');
        $bp     = self::basePath(); // örn: /osb_gate_mvc/public
        return $scheme . '://' . $host . ($bp === '' ? '' : $bp);
    }

    public static function to(string $path = ''): string {
        $noRewrite = (bool) Container::get('app.noRewrite', false);
        $p = '/' . ltrim($path, '/'); // /movements

        // ABSOLUTE URL üret (baseUrl + path)
        if ($noRewrite) {
            // http://localhost/osb_gate_mvc/public/index.php?r=/movements
            return self::baseUrl() . '/index.php?r=' . ltrim($p, '/');
        }
        // http://localhost/osb_gate_mvc/public/movements
        return self::baseUrl() . $p;
    }

    public static function asset(string $path): string {
        // http://localhost/osb_gate_mvc/public/assets/...
        return self::baseUrl() . '/' . ltrim($path, '/');
    }
}
