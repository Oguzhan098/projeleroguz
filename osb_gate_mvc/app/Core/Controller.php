<?php
declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $data['baseUrl'] = Url::baseUrl();
        $data['url']     = fn(string $path = '') => Url::to($path);

        extract($data, EXTR_OVERWRITE);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        $layout   = __DIR__ . '/../Views/layouts/main.php';

        ob_start();
        require $viewFile;
        $content = (string)ob_get_clean();
        require $layout;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . Url::to($path));
        exit;
    }

    public static function input(string $key, $default = null)
    {
        if (array_key_exists($key, $_POST)) return $_POST[$key];
        if (array_key_exists($key, $_GET))  return $_GET[$key];
        return $default;
    }
}
