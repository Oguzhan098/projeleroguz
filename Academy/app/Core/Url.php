<?php
namespace App\Core;

class Url
{
    public static function to(string $route, array $params = []): string
    {
        $q = http_build_query($params);
        return '/index.php?r=' . $route . ($q ? '&' . $q : '');
    }
}
