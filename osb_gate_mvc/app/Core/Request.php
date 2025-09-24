<?php
declare(strict_types=1);

namespace App\Core;

class Request {
    public function method(): string { return $_SERVER['REQUEST_METHOD'] ?? 'GET'; }
    public function input(string $key, $default=null) {
        return array_key_exists($key, $_POST) ? $_POST[$key]
            : (array_key_exists($key, $_GET) ? $_GET[$key] : $default);
    }
}
