<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, string>> */
    private array $routes = ['GET'=>[], 'POST'=>[]];
    private string $basePath = '';

    public function setBasePath(string $basePath): void {
        $this->basePath = $basePath;
    }

    public function get(string $path, string $handler): void { $this->routes['GET'][$path] = $handler; }
    public function post(string $path, string $handler): void { $this->routes['POST'][$path] = $handler; }

    public function dispatch(string $method, string $uri): void {
        $path = $this->stripBase($uri);
        $routes = $this->routes[strtoupper($method)] ?? [];
        foreach ($routes as $route => $handler) {
            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                [$controllerName, $action] = explode('@', $handler, 2);
                $fqcn = 'App\\Controllers\\' . $controllerName;
                if (!class_exists($fqcn)) throw new \RuntimeException("Controller not found: $fqcn");
                $controller = new $fqcn();
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }

    private function stripBase(string $uri): string {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        if ($this->basePath !== '' && strncmp($path, $this->basePath, strlen($this->basePath)) === 0) {
            $path = substr($path, strlen($this->basePath)) ?: '/';
        }
        return $path;
    }
}
