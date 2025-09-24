<?php declare(strict_types=1); ?>
<?php

if (!isset($basePath) || !is_string($basePath)) {
    $bp = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $basePath = ($bp === '/' || $bp === '.') ? '' : $bp;
}
if (!isset($baseUrl) || !is_string($baseUrl)) {
    $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $baseUrl = $scheme . '://' . $host . ($basePath === '' ? '' : $basePath);
}
if (!isset($url) || !is_callable($url)) {
    $reqUri = $_SERVER['REQUEST_URI'] ?? '';
    $noRewrite = (strpos($reqUri, 'index.php') !== false);
    $url = function (string $path = '') use ($basePath, $noRewrite): string {
        $p = '/' . ltrim($path, '/');
        return $basePath . ($noRewrite ? ('/index.php?r=' . ltrim($p, '/')) : $p);
    };
}
// -----------------------------------
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Uçuş Yönetimi (MVC)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= htmlspecialchars($baseUrl . '/', ENT_QUOTES, 'UTF-8') ?>">
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;margin:40px;}
        header,footer{margin-bottom:20px;}
        .container{max-width:1000px;margin:0 auto;}
        table{border-collapse:collapse;width:100%;}
        th,td{border:1px solid #ddd;padding:8px;text-align:left;}
        th{background:#f7f7f7;}
        .error{background:#ffecec;color:#b10000;padding:10px;border:1px solid #ffb3b3;margin-bottom:12px;}
        .row{margin-bottom:8px;}
        input,select{padding:6px 8px;width:100%;max-width:360px;}
        .actions a,.actions button{display:inline-block;margin-right:8px;}
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Uçuş Yönetimi</h1>
        <nav>
            <a href="<?= htmlspecialchars($url('/flights'), ENT_QUOTES, 'UTF-8') ?>">Uçuşlar</a> |
            <a href="<?= htmlspecialchars($url('/flights/create'), ENT_QUOTES, 'UTF-8') ?>">Yeni Uçuş</a>
        </nav>
        <hr>
    </header>
    <main>
        <?= $content ?? '' ?>
    </main>
    <footer>
        <hr>
        <small>© <?= date('Y') ?> Uçuş Sistemi</small>
    </footer>
</div>
</body>
</html>
