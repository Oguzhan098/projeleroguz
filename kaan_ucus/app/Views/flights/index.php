<?php declare(strict_types=1);

if (!isset($url) || !is_callable($url)) {
    $bp = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($bp === '/' || $bp === '.') { $bp = ''; }
    $url = function (string $path = '') use ($bp): string {
        return $bp . '/' . ltrim($path, '/');
    };
}

$flights = (isset($flights) && is_array($flights)) ? $flights : [];
$error   = $error ?? '';
?>
<h2>Uçuş Listesi</h2>

<?php if (!empty($error)) : ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<p>
    <a href="<?= htmlspecialchars($url('/flights/create'), ENT_QUOTES, 'UTF-8') ?>">Yeni Uçuş</a>
</p>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Kalkış</th>
        <th>Varış</th>
        <th>Uçak</th>
        <th>Başlangıç</th>
        <th>Bitiş</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($flights)) : ?>
        <tr><td colspan="7">Uçuş bulunamadı.</td></tr>
    <?php else: ?>
        <?php foreach ($flights as $f): ?>
            <tr>
                <td><?= htmlspecialchars((string)($f['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($f['dep_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($f['arr_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($f['plane_model'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($f['departure_ts'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($f['arrival_ts'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="actions">
                    <a href="<?= htmlspecialchars($url('/flights/' . (string)($f['id'] ?? '') . '/edit'), ENT_QUOTES, 'UTF-8') ?>">Düzenle</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>