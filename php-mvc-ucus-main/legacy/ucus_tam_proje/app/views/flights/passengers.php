<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$pdo = require __DIR__ . '/../../config/database.php';

$flightId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($flightId <= 0) {
    http_response_code(400);
    echo "Geçersiz uçuş."; exit;
}

$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'add' && !empty($_POST['person_id'])) {
            $stmt = $pdo->prepare("INSERT INTO flight_person (flight_id, person_id) VALUES (:f,:p)");
            $stmt->execute([':f'=>$flightId, ':p'=>(int)$_POST['person_id']]);
            $_SESSION['flash'] = 'Yolcu eklendi.';

            header("Location: ?id=".$flightId); exit;

        } elseif ($action === 'remove' && !empty($_POST['person_id'])) {
            $stmt = $pdo->prepare("DELETE FROM flight_person WHERE flight_id=:f AND person_id=:p");
            $stmt->execute([':f'=>$flightId, ':p'=>(int)$_POST['person_id']]);
            $_SESSION['flash'] = 'Yolcu çıkarıldı.';

            header("Location: ?id=".$flightId); exit;
        }
    } catch (Throwable $e) {
        $flash = 'Hata: '.$e->getMessage();
    }
}

$flight = $pdo->prepare(
    "SELECT f.id, dep.name dep_airport, arr.name arr_airport, f.departure_ts, f.arrival_ts, p.model plane_model
   FROM flights f
   JOIN airport dep ON dep.id=f.departure_airport_id
   JOIN airport arr ON arr.id=f.arrival_airport_id
   JOIN plane p ON p.id=f.plane_id
   WHERE f.id=:id");
$flight->execute([':id'=>$flightId]);
$flight = $flight->fetch();
if (!$flight) { echo "Uçuş bulunamadı."; exit; }

$currPs = $pdo->prepare(
    "SELECT pe.id, pe.first_name, pe.last_name
   FROM flight_person fp
   JOIN person pe ON pe.id=fp.person_id
   WHERE fp.flight_id=:id
   ORDER BY pe.id");
$currPs->execute([':id'=>$flightId]);
$currentPassengers = $currPs->fetchAll();

$allPeople = $pdo->query("SELECT id, first_name, last_name FROM person ORDER BY id")->fetchAll();

if (!function_exists('e')) { function e($v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); } }
$BASE = $BASE ?? null;

require __DIR__ . '/../layout/header.php';
?>
<h1>Uçuş Yolcularını Yönet</h1>

<div class="card">
    <div><b>Uçuş #<?= (int)$flight['id'] ?></b></div>
    <div><?= e($flight['dep_airport']) ?> → <?= e($flight['arr_airport']) ?></div>
    <div><?= e($flight['departure_ts']) ?> — <?= e($flight['arrival_ts']) ?> (<?= e($flight['plane_model']) ?>)</div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
<?php endif; ?>
<?php if (!empty($flash)): ?>
    <div class="flash"><?= e($flash) ?></div>
<?php endif; ?>

<div class="form-grid" style="align-items:end;">
    <div>
        <label>Yolcu Ekle</label>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <select name="person_id" required>
                <option value="">Seçin</option>
                <?php foreach ($allPeople as $pe): ?>
                    <option value="<?= (int)$pe['id'] ?>">
                        <?= e($pe['first_name'].' '.$pe['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn" type="submit">Ekle</button>
        </form>
    </div>
</div>

<h2>Mevcut Yolcular</h2>
<?php if (empty($currentPassengers)): ?>
    <p class="small">Bu uçuşta yolcu yok.</p>
<?php else: ?>
    <table>
        <thead><tr><th>#</th><th>Ad Soyad</th><th></th></tr></thead>
        <tbody>
        <?php $i=1; foreach ($currentPassengers as $pe): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= e($pe['first_name'].' '.$pe['last_name']) ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Yolcu çıkarılsın mı?')">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="person_id" value="<?= (int)$pe['id'] ?>">
                        <button class="btn danger" type="submit">Çıkar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="actions">
    <a class="btn secondary" href="<?= $BASE ?>/app/views/flights/index.php">← Uçuşlar</a>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
