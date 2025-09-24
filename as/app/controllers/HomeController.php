<?php
class HomeController {
    public function index() {
        $title = "Hoşgeldiniz";
        include VIEW_PATH . '/legacy/index.php';
    }
}
