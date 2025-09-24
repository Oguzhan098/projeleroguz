<?php declare(strict_types=1);

if (!isset($url) || !is_callable($url)) {
    $bp = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($bp === '/' || $bp === '.') { $bp = ''; }
    $url = function (string $path = '') use ($bp): string {
        return $bp . '/' . ltrim($path, '/');
    };
}

$flight = (isset($flight) && is_array($flight)) ? $flight : [
        'id' => '',
        'departure_airport_id' => '',
        'arrival_airport_id'   => '',
        'plane_id'             => '',
        'departure_ts'         => '',
        'arrival_ts'           => '',
];
$error  = $error ?? '';
?>
<h2>Uçuş Düzenle #<?= htmlspecialchars((string)($flight['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($url('/flights/' . (string)($flight['id'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
    <div class="row">
        <label>Kalkış Havalimanı ID</label><br>
        <input name="departure_airport_id" type="number" required
               value="<?= htmlspecialchars((string)($flight['departure_airport_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Varış Havalimanı ID</label><br>
        <input name="arrival_airport_id" type="number" required
               value="<?= htmlspecialchars((string)($flight['arrival_airport_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Uçak ID</label><br>
        <input name="plane_id" type="number" required
               value="<?= htmlspecialchars((string)($flight['plane_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Kalkış Zamanı</label><br>
        <input name="departure_ts" type="text" required
               value="<?= htmlspecialchars((string)($flight['departure_ts'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Varış Zamanı</label><br>
        <input name="arrival_ts" type="text" required
               value="<?= htmlspecialchars((string)($flight['arrival_ts'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <button type="submit">Güncelle</button>
</form>
