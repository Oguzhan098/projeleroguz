<?php
namespace App\Core;

class Request
{
    public static function get(string $key, $default=null) { return $_GET[$key] ?? $default; }
    public static function post(string $key, $default=null) { return $_POST[$key] ?? $default; }
    public static function int(string $key, $default=0, bool $fromPost=false): int
    {
        $v = $fromPost ? ($_POST[$key] ?? null) : ($_GET[$key] ?? null);
        return is_numeric($v) ? (int)$v : $default;
    }
}
