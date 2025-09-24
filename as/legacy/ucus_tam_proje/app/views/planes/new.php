<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$BASE = '/ucus_tam_proje';

$pdo = require __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO plane (brand,model,capacity,year) VALUES (:b,:m,:c,:y)");
    $stmt->execute([
        ':b' => ($_POST['brand'] ?? '') !== '' ? trim((string)$_POST['brand']) : null,
        ':m' => trim((string)$_POST['model']),
        ':c' => (int)$_POST['capacity'],
        ':y' => ($_POST['year'] ?? '') !== '' ? (int)$_POST['year'] : null,
    ]);
    $_SESSION['flash'] = 'Uçak eklendi.';
    header("Location: {$BASE}/app/views/planes/index.php");
    exit;
}

require __DIR__ . '/../layout/header.php';
?>
<h1>Yeni Uçak</h1>

<form method="post" action="<?= $BASE ?>/app/views/planes/new.php" class="form-grid">
    <div><label>Marka</label><input type="text" name="brand"></div>
    <div><label>Model</label><input type="text" name="model" required></div>
    <div><label>Kapasite</label><input type="number" name="capacity" min="1" required></div>
    <div><label>Yıl</label><input type="number" name="year" min="1950" max="2100"></div>
    <div style="grid-column:1/-1">
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn secondary" href="<?= $BASE ?>/app/views/planes/index.php">İptal</a>
    </div>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>
