<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OSB Giriş-Çıkış</title>

    <!-- Basit UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(\App\Core\Url::asset('assets/css/app.css')) ?>">
</head>
<body>
<nav>
    <ul><li><strong>OSB Giriş-Çıkış</strong></li></ul>
    <ul>
        <?php if (\App\Core\Auth::check()): ?>
            <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/')) ?>">Gösterge Paneli</a></li>
            <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Hareketler</a></li>
            <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/movements/create')) ?>">Yeni Kayıt</a></li>
            <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/reports/stats')) ?>">İstatistikler</a></li>
            <li><a class="contrast" href="<?= htmlspecialchars(\App\Core\Url::to('/logout')) ?>">Çıkış (<?= htmlspecialchars(\App\Core\Auth::user() ?? '') ?>)</a></li>
        <?php else: ?>
            <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/login')) ?>">Giriş Yap</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <?= $content ?>
</main>

<!-- İstersen global yükle: -->
<!-- <script defer src="<?= htmlspecialchars(\App\Core\Url::asset('assets/js/script.js')) ?>"></script> -->
</body>
</html>
