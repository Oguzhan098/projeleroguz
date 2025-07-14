<?php
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt->execute([$username, $password])) {
        header("Location: login.php");
    } else {
        echo "Kayıt başarısız.";
    }
}
?>

    <form method="post">
        <input name="username" placeholder="Kullanıcı Adı" required>
        <input name="password" type="password" placeholder="Şifre" required>
        <button type="submit">Kayıt Ol</button>
    </form>
<?php
