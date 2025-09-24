<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit;
    } else {
        echo "Hatalı kullanıcı adı veya şifre.";
    }
}
?>

<h2>Giriş Yap</h2>
<form method="post">
    Kullanıcı Adı: <input name="username"><br>
    Şifre: <input type="password" name="password"><br>
    <button type="submit">Giriş Yap</button>
</form>
