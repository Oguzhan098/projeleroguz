<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$pdo = require __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO flights (departure_airport_id,arrival_airport_id,plane_id,departure_ts,arrival_ts)
                               VALUES (:dep,:arr,:plane,:dts,:ats) RETURNING id");
        $stmt->execute([
            ':dep'   => (int)$_POST['departure_airport_id'],
            ':arr'   => (int)$_POST['arrival_airport_id'],
            ':plane' => (int)$_POST['plane_id'],
            ':dts'   => (string)$_POST['departure_ts'],
            ':ats'   => (string)$_POST['arrival_ts'],
        ]);
        $fid = (int)$stmt->fetchColumn();

        if (!empty($_POST['passenger_ids']) && is_array($_POST['passenger_ids'])) {
            $ins = $pdo->prepare("INSERT INTO flight_person (flight_id, person_id) VALUES (:f,:p)");
            foreach ($_POST['passenger_ids'] as $pid) {
                $ins->execute([':f'=>$fid, ':p'=>(int)$pid]);
            }
        }
        $pdo->commit();
        $_SESSION['flash'] = 'Uçuş eklendi.';
        header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        $_SESSION['flash'] = 'Hata: ' . $e->getMessage();
    }
}

$airports = $pdo->query("SELECT id, name FROM airport ORDER BY name")->fetchAll();
/* BRAND + MODEL birlikte almak için sorguyu güncelledik */
$planes   = $pdo->query("SELECT id, brand, model FROM plane ORDER BY id")->fetchAll();
$people   = $pdo->query("SELECT id, first_name, last_name FROM person ORDER BY id")->fetchAll();


if (!function_exists('e')) { function e($v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); } }

require __DIR__ . '/../layout/header.php';
?>
<h1>Yeni Uçuş</h1>

<form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>" class="form-grid">
    <div>
        <label>Kalkış Havalimanı</label>
        <select name="departure_airport_id" required>
            <option value="">Seçin</option>
            <?php foreach ($airports as $a): ?>
                <option value="<?= (int)$a['id'] ?>"><?= e($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Varış Havalimanı</label>
        <select name="arrival_airport_id" required>
            <option value="">Seçin</option>
            <?php foreach ($airports as $a): ?>
                <option value="<?= (int)$a['id'] ?>"><?= e($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Kalkış Zamanı</label>
        <input type="datetime-local" name="departure_ts" required>
    </div>

    <div>
        <label>Varış Zamanı</label>
        <input type="datetime-local" name="arrival_ts" required>
    </div>

    <div>
        <label>Uçak (Marka — Model)</label>
        <select name="plane_id" required>
            <option value="">Seçin</option>
            <?php foreach ($planes as $p): ?>
                <?php
                $brand = $p['brand'] ?? '';
                $model = $p['model'] ?? '';
                $label = ($brand && $model) ? ($brand . ' — ' . $model) : ($brand ?: $model);
                ?>
                <option value="<?= (int)$p['id'] ?>"><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Yolcular (çoklu seçim)</label>
        <select name="passenger_ids[]" multiple size="6">
            <?php foreach ($people as $pe): ?>
                <option value="<?= (int)$pe['id'] ?>"><?= e($pe['first_name'] . ' ' . $pe['last_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="grid-column:1/-1; display:flex; gap:8px;">
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn secondary" href="<?= dirname($_SERVER['SCRIPT_NAME']) . '/index.php' ?>">İptal</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
