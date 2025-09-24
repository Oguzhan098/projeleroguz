<?php
// Minimal Router
class Router {
    public static function dispatch() {
        $controller = isset($_GET['c']) ? $_GET['c'] : 'Home';
        $action = isset($_GET['a']) ? $_GET['a'] : 'index';
        $controllerClass = $controller . 'Controller';

        $controllerFile = APP_ROOT . '/controllers/' . $controllerClass . '.php';
        if (!file_exists($controllerFile)) {
            header("HTTP/1.0 404 Not Found");
            echo "Controller not found";
            exit;
        }
        require_once $controllerFile;
        if (!class_exists($controllerClass)) {
            header("HTTP/1.0 500");
            echo "Controller class missing";
            exit;
        }
        $obj = new $controllerClass();
        if (!method_exists($obj, $action)) {
            header("HTTP/1.0 404 Not Found");
            echo "Action not found";
            exit;
        }
        call_user_func([$obj, $action]);
    }
}
