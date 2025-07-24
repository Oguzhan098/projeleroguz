<?php


class Router
{
    private $getRoutes = [];
    private $postRoutes = [];

    public function get($route, $callback)
    {
        $this->getRoutes[$route] = $callback;
    }

    public function post($route, $callback)
    {
        $this->postRoutes[$route] = $callback;
    }

    public function dispatch($uri, $method)
    {
        $routes = $method === 'POST' ? $this->postRoutes : $this->getRoutes;

        foreach ($routes as $pattern => $callback) {
            $regex = "@^" . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $pattern) . "$@D";

            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_string($callback) && strpos($callback, '@') !== false) {
                    [$class, $method] = explode('@', $callback);
                    require_once $class . '.php';
                    return call_user_func_array([new $class, $method], $params);
                }

                return call_user_func_array($callback, $params);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
