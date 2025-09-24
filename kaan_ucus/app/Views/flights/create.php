<?php declare(strict_types=1);

if (!isset($url) || !is_callable($url)) {
    $bp = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($bp === '/' || $bp === '.') { $bp = ''; }
    $url = function (string $path = '') use ($bp): string {
        return $bp . '/' . ltrim($path, '/');
    };
}

$data  = (isset($data) && is_array($data)) ? $data : [];
$error = $error ?? '';
?>
<h2>Yeni Uçuş</h2>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($url('/flights'), ENT_QUOTES, 'UTF-8') ?>">
    <div class="row">
        <label>Kalkış Havalimanı ID</label><br>
        <input name="departure_airport_id" type="number" min="1" required
               value="<?= htmlspecialchars((string)($data['departure_airport_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Varış Havalimanı ID</label><br>
        <input name="arrival_airport_id" type="number" min="1" required
               value="<?= htmlspecialchars((string)($data['arrival_airport_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Uçak ID</label><br>
        <input name="plane_id" type="number" min="1" required
               value="<?= htmlspecialchars((string)($data['plane_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Kalkış Zamanı (YYYY-MM-DD HH:MM:SS)</label><br>
        <input name="departure_ts" type="text" required
               value="<?= htmlspecialchars((string)($data['departure_ts'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="row">
        <label>Varış Zamanı (YYYY-MM-DD HH:MM:SS)</label><br>
        <input name="arrival_ts" type="text" required
               value="<?= htmlspecialchars((string)($data['arrival_ts'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <button type="submit">Kaydet</button>
</form>
