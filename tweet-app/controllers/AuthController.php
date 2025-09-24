<?php
class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (User::login($_POST['username'], $_POST['password'])) {
                header("Location: /");
                exit;
            } else {
                $error = "Kullanıcı adı veya şifre yanlış.";
            }
        }
        include 'views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            User::register($_POST['username'], $_POST['password']);
            header("Location: /login");
            exit;
        }
        include 'views/register.php';
    }

    public function logout() {
        User::logout();
        header("Location: /");
    }
}