<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Tweet App</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1><a href="/">Tweet App</a></h1>
        <nav>
            <?php if (User::isLoggedIn()): ?>
                Hoşgeldin, <?= $_SESSION['username'] ?> | <a href="/logout">Çıkış</a>
            <?php else: ?>
                <a href="/login">Giriş</a> | <a href="/register">Kayıt Ol</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>