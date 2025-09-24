<?php
declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {

        $basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($basePath === '/' || $basePath === '.') { $basePath = ''; }

        $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host . ($basePath === '' ? '' : $basePath);

        $reqUri = $_SERVER['REQUEST_URI'] ?? '';
        $noRewrite = (strpos($reqUri, 'index.php') !== false);

        $url = function (string $path = '') use ($basePath, $noRewrite): string {
            $p = '/' . ltrim($path, '/');
            return $basePath . ($noRewrite ? ('/index.php?r=' . ltrim($p, '/')) : $p);
        };

        $data['basePath'] = $basePath;
        $data['baseUrl']  = $baseUrl;
        $data['url']      = $url;

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
        $basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
        header('Location: ' . $basePath . '/' . ltrim($path, '/'));
        exit;
    }


    public static function input(string $key, $default = null)
    {
        if (array_key_exists($key, $_POST)) return $_POST[$key];
        if (array_key_exists($key, $_GET))  return $_GET[$key];
        return $default;
    }
}
