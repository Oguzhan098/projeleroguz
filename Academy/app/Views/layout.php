<?php /* Basit layout. Pico.css CDN kullanıyoruz. */ ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= $title ?? 'Academy' ?></title>
<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
<link rel="stylesheet" href="/assets/css/app.css">
</head>


<body>
<header class="container">
<nav>
<ul>
<li><strong>Academy</strong></li>
</ul>
<ul>
<li><a href="/index.php?r=home/index">Ana Sayfa</a></li>
<li><a href="/index.php?r=students/index">Öğrenciler</a></li>
<li><a href="/index.php?r=courses/index">Dersler</a></li>
<li><a href="/index.php?r=instructors/index">Eğitmenler</a></li>
<li><a href="/index.php?r=enrollments/index">Kayıtlar</a></li>
    <li><a href="/index.php?r=custodians/index">Veliler</a></li>
</ul>
</nav>
</header>
<main class="container">
    <?php if ($m = \App\Core\Flash::get('success')): ?>
        <div class="contrast" style="padding:.6rem 1rem;border-left:4px solid #2ecc71;margin-bottom:1rem;">
            <?= htmlspecialchars($m) ?>
        </div>
    <?php endif; ?>
    <?php if ($m = \App\Core\Flash::get('error')): ?>
        <div class="contrast" style="padding:.6rem 1rem;border-left:4px solid #e74c3c;margin-bottom:1rem;">
            <?= htmlspecialchars($m) ?>
        </div>
    <?php endif; ?>
    <?php if (isset($viewFile) && file_exists($viewFile)) { include $viewFile; } else { echo '<p>View bulunamadı.</p>'; } ?></main>
<script src="/assets/js/app.js"></script>
</body>
</html>