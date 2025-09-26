<?php
namespace App\Core;


class Router
{
    public function dispatch(): void
    {
        $route = $_GET['r'] ?? 'home/index';
        [$controller, $action] = array_pad(explode('/', trim($route, '/')), 2, 'index');
        $controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';
        $actionMethod = $action;


        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller $controllerClass not found";
            return;
        }
        $controllerObj = new $controllerClass();
        if (!method_exists($controllerObj, $actionMethod)) {
            http_response_code(404);
            echo "Action $actionMethod not found";
            return;
        }
        $controllerObj->$actionMethod();
    }
}