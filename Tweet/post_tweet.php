<?php
require 'includes/config.php';
require 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['tweet'])) {
    $tweet = substr(trim($_POST['tweet']), 0, 180);
    $pdo = new PDO("pgsql:host=localhost;dbname=oguz", "postgres", "postgres");
    $stmt = $pdo->prepare("INSERT INTO tweets (user_id, content) VALUES (?, ?)");
    $stmt->execute([getUserId(), $tweet]);

}

header("Location: index.php");
?>
