<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OSB Giriş-Çıkış</title>

    <!-- Framework (opsiyonel) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <!-- Proje CSS -->
    <link rel="stylesheet" href="<?= htmlspecialchars(\App\Core\Url::asset('assets/css/app.css')) ?>">
</head>
<body>
<nav>
    <ul><li><strong>OSB Giriş-Çıkış</strong></li></ul>
    <ul>
        <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/')) ?>">Gösterge Paneli</a></li>
        <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Hareketler</a></li>
        <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/movements/create')) ?>">Yeni Kayıt</a></li>
        <li><a href="<?= htmlspecialchars(\App\Core\Url::to('/reports/daily')) ?>">Günlük Rapor</a></li>
    </ul>
</nav>
<main>
    <?= $content ?>
</main>
</body>
</html>
