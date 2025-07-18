<?php

class Router
{
    private $routes = ['GET' => [], 'POST' => []];

    public function get($pattern, $callback)
    {
        $this->addRoute('GET', $pattern, $callback);
    }

    public function post($pattern, $callback)
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    private function addRoute($method, $pattern, $callback)
    {
        // {id} -> (?P<id>[^/]+)
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<\1>[^/]+)', $pattern);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function dispatch($method, $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->call($route['callback'], $params);
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }

    private function call($callback, $params)
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($class, $method) = explode('@', $callback);
            require_once "controllers/{$class}.php";
            $controller = new $class;
            return call_user_func_array([$controller, $method], $params);
        }

        throw new Exception("Geçersiz callback tanımı.");
    }
}
