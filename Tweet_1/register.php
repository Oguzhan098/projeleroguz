<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    header("Location: login.php");
}
?>

<h2>Kayıt Ol</h2>
<form method="post">
    Kullanıcı Adı: <input name="username"><br>
    Şifre: <input type="password" name="password"><br>
    <button type="submit">Kayıt Ol</button>
</form>
