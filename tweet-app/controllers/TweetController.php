<?php
class TweetController {
    public function home() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && User::isLoggedIn()) {
            Tweet::create($_SESSION['user_id'], $_POST['content']);
            header("Location: /");
            exit;
        }

        $tweets = Tweet::getAll();
        include 'views/home.php';
    }
}