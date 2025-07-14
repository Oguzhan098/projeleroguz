<?php
require 'includes/config.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
    } else {
        echo "Hatalı giriş.";
    }
}
?>

    <form method="post">
        <input name="username" placeholder="Kullanıcı Adı" required>
        <input name="password" type="password" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>
<?php
