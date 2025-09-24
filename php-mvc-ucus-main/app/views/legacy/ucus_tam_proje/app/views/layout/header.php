<?php

if (!isset($BASE) || !$BASE) {

    $BASE = preg_replace('#/app/.*$#', '', $_SERVER['SCRIPT_NAME']);
    if ($BASE === null || $BASE === '') { $BASE = '/'; }
}
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

if (!function_exists('e')) {
    function e($v): string {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uçuş Yönetimi</title>
    <link rel="stylesheet" href="<?= $BASE ?>/public/assets/styles.css">
</head>
<body>
<nav class="topnav">
    <a href="<?= $BASE ?>/app/views/flights/index.php">Uçuşlar</a>
    <a href="<?= $BASE ?>/app/views/airports/index.php">Havalimanları</a>
    <a href="<?= $BASE ?>/app/views/planes/index.php">Uçaklar</a>
    <a href="<?= $BASE ?>/app/views/people/index.php">Kişiler</a>
</nav>
<main class="container">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>


