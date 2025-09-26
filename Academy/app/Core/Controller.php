<?php
namespace App\Core;


class Controller
{
    protected function render(string $template, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        $viewFile = __DIR__ . '/../Views/' . $template . '.php';
        include __DIR__ . '/../Views/layout.php';
    }
}