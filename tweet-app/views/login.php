<?php ob_start(); ?>
<h2>Giriş Yap</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Kullanıcı Adı" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Giriş</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
<?php $content = ob_get_clean(); include 'layout.php'; ?>