<?php
if(!isset($page)) $page='';
?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'CourseApp'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">CourseApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?php echo $page==='home'?'active':''; ?>" href="index.php">Anasayfa</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $page==='courses'?'active':''; ?>" href="kurslar.php">Kurslar</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $page==='programming'?'active':''; ?>" href="programlama.php">Programlama</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $page==='web'?'active':''; ?>" href="webgelistirme.php">Web Geliştirme</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $page==='mobile'?'active':''; ?>" href="mobil.php">Mobil Uygulama</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="container my-4">
