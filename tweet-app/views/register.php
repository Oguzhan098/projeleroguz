<?php ob_start(); ?>
<h2>Kayıt Ol</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Kullanıcı Adı" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Kayıt Ol</button>
</form>
<?php $content = ob_get_clean(); include 'layout.php'; ?>