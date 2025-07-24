<?php


class HomeController
{
    public function index()
    {
        echo "<h1>Ana Sayfa</h1>";
    }

    public function hello($name)
    {
        echo "<h1>Merhaba, $name!</h1>";
    }
}
