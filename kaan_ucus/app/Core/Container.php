<?php
namespace App\Core;

final class Container {
    private static $items = [];

    public static function set(string $key, $value): void {
        self::$items[$key] = $value;
    }

    public static function get(string $key, $default = null) {
        return array_key_exists($key, self::$items) ? self::$items[$key] : $default;
    }
}
