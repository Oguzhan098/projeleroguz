<?php
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$pdo  = require __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $fid = (int)$_POST['delete_id'];
    $pdo->prepare("DELETE FROM flight_person WHERE flight_id = :id")->execute([':id'=>$fid]);
    $pdo->prepare("DELETE FROM flights WHERE id = :id")->execute([':id'=>$fid]);
    $_SESSION['flash'] = 'Uçuş silindi.';
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

$sql = "SELECT f.id,
               dep.name AS dep_airport, arr.name AS arr_airport,
               f.departure_ts, f.arrival_ts,
               p.model AS plane_model,
               (SELECT COALESCE(json_agg(json_build_object('first_name', pe.first_name, 'last_name', pe.last_name)), '[]'::json)
                FROM flight_person fp JOIN person pe ON pe.id = fp.person_id
                WHERE fp.flight_id = f.id) AS passengers
        FROM flights f
        JOIN airport dep ON dep.id = f.departure_airport_id
        JOIN airport arr ON arr.id = f.arrival_airport_id
        JOIN plane   p   ON p.id   = f.plane_id
        ORDER BY f.departure_ts DESC";
$flights = $pdo->query($sql)->fetchAll();

if (!function_exists('e')) { function e($v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); } }
$BASE = $BASE ?? null;
require __DIR__ . '/../layout/header.php';
?>
<h1>Uçuşlar</h1>
<div class="actions">
    <a class="btn" href="<?= $BASE ?>/app/views/flights/new.php">Yeni Uçuş</a>
</div>
<table>
    <thead><tr>
        <th>Sıra</th>
        <th>Kalkış</th>
        <th>Varış</th>
        <th>Kalkış Zamanı</th>
        <th>Varış Zamanı</th>
        <th>Uçak</th>
        <th>Yolcular</th>
        <th>Aksiyon</th>
    </tr></thead>
    <tbody>
    <?php $i=1; foreach ($flights as $f): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= e($f['dep_airport']) ?></td>
            <td><?= e($f['arr_airport']) ?></td>
            <td><?= date('d.m.Y H:i', strtotime($f['departure_ts'])) ?></td>
            <td><?= date('d.m.Y H:i', strtotime($f['arrival_ts'])) ?></td>
            <td><?= e($f['plane_model']) ?></td>
            <td class="small">
                <?php
                $passengers = $f['passengers'];
                if (is_string($passengers)) $passengers = json_decode($passengers, true);
                if (is_array($passengers)) {
                    foreach ($passengers as $p) echo e(($p['first_name']??'').' '.($p['last_name']??'')) .
                            '<br>';
                }
                ?>
            </td>
            <td class="actions" style="display:flex; gap:6px;">
                <a class="btn" href="<?= $BASE ?>/app/views/flights/passengers.php?id=<?=
                (int)$f['id'] ?>">Yolcuları Yönet</a>
                <form method="post"
                      action="<?= $BASE ?>/app/views/flights/index.php" o
                      nsubmit="return confirm('Uçuş silinsin mi?')">

                    <input type="hidden" name="delete_id" value="<?= (int)$f['id'] ?>">
                    <button class="btn danger" type="submit">Sil</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>
