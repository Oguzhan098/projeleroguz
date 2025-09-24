<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$BASE = '/ucus_tam_proje';

$pdo = require __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO airport (name,pist_sayisi,ucak_kapasitesi) VALUES (:n,:p,:u)");
    $stmt->execute([
        ':n' => trim((string)$_POST['name']),
        ':p' => (int)$_POST['pist_sayisi'],
        ':u' => (int)$_POST['ucak_kapasitesi'],
    ]);
    $_SESSION['flash'] = 'Havalimanı eklendi.';
    header("Location: {$BASE}/app/views/airports/index.php");
    exit;
}

require __DIR__ . '/../layout/header.php';
?>
<h1>Yeni Havalimanı</h1>

<form method="post" action="<?= $BASE ?>/app/views/airports/new.php" class="form-grid">
    <div><label>Ad</label><input type="text" name="name" required></div>
    <div><label>Pist Sayısı</label><input type="number" name="pist_sayisi" min="1" required></div>
    <div><label>Uçak Kapasitesi</label><input type="number" name="ucak_kapasitesi" min="1" required></div>
    <div style="grid-column:1/-1">
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn secondary" href="<?= $BASE ?>/app/views/airports/index.php">İptal</a>
    </div>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>
