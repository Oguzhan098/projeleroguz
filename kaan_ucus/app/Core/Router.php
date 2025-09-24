<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [
        'GET' => [], 'POST' => [], 'PUT' => [], 'PATCH' => [], 'DELETE' => []
    ];

    private string $basePath = '';

    public function setBasePath(string $basePath): void {
        $basePath = rtrim($basePath, '/');
        $this->basePath = ($basePath === '/') ? '' : $basePath;
    }

    public function get(string $path, string $action): void    { $this->add('GET', $path, $action); }
    public function post(string $path, string $action): void   { $this->add('POST', $path, $action); }
    public function put(string $path, string $action): void    { $this->add('PUT', $path, $action); }
    public function patch(string $path, string $action): void  { $this->add('PATCH', $path, $action); }
    public function delete(string $path, string $action): void { $this->add('DELETE', $path, $action); }

    private function add(string $method, string $path, string $action): void {
        $this->routes[$method][$this->normalize($path)] = $action;
    }

    private function normalize(string $path): string {
        $p = '/' . trim($path, '/');
        return $p === '//' ? '/' : $p;
    }

    private static function startsWith(string $haystack, string $needle): bool {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    public function dispatch(string $method, string $uri): void {
        if ($method === 'HEAD') $method = 'GET';

        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        if ($this->basePath !== '' && self::startsWith($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
            if ($path === '' || $path === false) { $path = '/'; }
        }
        $path = $this->normalize($path);

        $match = $this->match($method, $path);
        if ($match === null) {
            if ($this->pathExistsOnAnotherMethod($method, $path)) {
                http_response_code(405); echo '405 Method Not Allowed'; return;
            }
            http_response_code(404); echo '404 Not Found'; return;
        }

        [$controller, $methodName, $params] = $match;
        $controllerClass = 'App\\Controllers\\' . $controller;
        if (!class_exists($controllerClass)) { http_response_code(500); echo "Controller not found: $controllerClass"; return; }
        $controllerObj = new $controllerClass();
        if (!method_exists($controllerObj, $methodName)) { http_response_code(500); echo "Method not found: $controllerClass::$methodName"; return; }

        array_walk($params, static function (&$v): void { if (is_string($v)) $v = urldecode($v); });
        call_user_func_array([$controllerObj, $methodName], array_values($params));
    }

    private function match(string $method, string $path): ?array {
        if (isset($this->routes[$method][$path])) {
            return $this->explodeAction($this->routes[$method][$path], []);
        }
        foreach ($this->routes[$method] as $route => $action) {
            $paramNames = [];
            $pattern = preg_replace_callback('#\{([^/]+)\}#', function(array $m) use (&$paramNames): string {
                $paramNames[] = $m[1]; return '([^/]+)';
            }, $route);
            $pattern = '#^' . str_replace('#', '\#', $pattern) . '$#';
            if (preg_match($pattern, $path, $m)) {
                array_shift($m);
                $params = [];
                foreach ($m as $i => $val) { $params[$paramNames[$i] ?? (string)$i] = $val; }
                return $this->explodeAction($action, $params);
            }
        }
        return null;
    }

    private function pathExistsOnAnotherMethod(string $method, string $path): bool {
        foreach ($this->routes as $m => $map) {
            if ($m === $method) continue;
            if (isset($map[$path])) return true;
            foreach ($map as $route => $_) {
                $pattern = '#^' . str_replace('#', '\#', preg_replace('#\{([^/]+)\}#', '([^/]+)', $route)) . '$#';
                if (preg_match($pattern, $path)) return true;
            }
        }
        return false;
    }

    private function explodeAction(string $action, array $params): array {
        [$c,$m] = explode('@', $action, 2);
        return [$c,$m,$params];
    }
}
