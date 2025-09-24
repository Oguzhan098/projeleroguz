<?php
declare(strict_types=1);

namespace App\Core;

final class Container {
    /** @var array<string, mixed> */
    private static $items = [];

    public static function set(string $key, $value): void {
        self::$items[$key] = $value;
    }
    public static function get(string $key, $default = null) {
        return array_key_exists($key, self::$items) ? self::$items[$key] : $default;
    }
    public static function has(string $key): bool {
        return array_key_exists($key, self::$items);
    }
    public static function clear(): void {
        self::$items = [];
    }
}
