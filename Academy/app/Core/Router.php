<?php
namespace App\Core;


class Router
{
    public function dispatch(): void
    {
        $route = $_GET['r'] ?? 'home/index';
        [$controller, $action] = array_pad(explode('/', trim($route, '/')), 2, 'index');

        $controller = preg_replace('/[^a-z0-9]/i', '', (string)$controller);
        $action     = preg_replace('/[^a-z0-9]/i', '', (string)$action) ?: 'index';

        $map = [
            'home'        => 'Home',
            'students'    => 'Students',
            'courses'     => 'Courses',
            'instructors' => 'Instructors',
            'enrollments' => 'Enrollments',
            'custodians' => 'Custodians',
        ];
        $controllerNorm = $map[strtolower($controller)] ?? ucfirst(strtolower($controller));

        $controllerClass = 'App\\Controllers\\' . $controllerNorm . 'Controller';

        error_log("[Router] r={$route}");
        error_log("[Router] controllerRaw={$controller} action={$action}");
        error_log("[Router] resolvedClass={$controllerClass}");

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller $controllerClass not found";
            return;
        }

        $controllerObj = new $controllerClass();
        if (!method_exists($controllerObj, $action)) {
            http_response_code(404);
            echo "Action $action not found";
            return;
        }
        $controllerObj->$action();
    }



}