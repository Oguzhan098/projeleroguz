<h2>İstatistikler</h2>

<form method="get" action="<?= htmlspecialchars(\App\Core\Url::to('/reports/stats')) ?>" class="grid" style="align-items:end;">
    <label>
        Başlangıç Tarihi
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date ?? '') ?>" required>
    </label>
    <label>
        Bitiş Tarihi
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date ?? '') ?>" required>
    </label>
    <label>
        Plaka (TR format – opsiyonel)
        <input type="text" name="plate" placeholder="34 ABC 123" value="<?= htmlspecialchars($plate ?? '') ?>">
    </label>
    <button type="submit">Uygula</button>
</form>

<?php
$tin  = (int)($totals['total_in']  ?? 0);
$tout = (int)($totals['total_out'] ?? 0);
$uniq = (int)($totals['distinct_vehicles'] ?? 0);
$avgm = (int)($avg_minutes ?? 0);
?>

<article class="mt-20">
    <header><strong>Özet</strong></header>
    <div class="grid">
        <div><h3>Giriş</h3><p class="stat"><?= $tin ?></p></div>
        <div><h3>Çıkış</h3><p class="stat"><?= $tout ?></p></div>
        <div><h3>Benzersiz Araç</h3><p class="stat"><?= $uniq ?></p></div>
        <div><h3>Ortalama Kalış (dk)</h3><p class="stat"><?= $avgm ?></p></div>
    </div>
</article>

<h3 class="mt-20">Saat Bazlı Giriş</h3>
<table role="grid">
    <thead><tr><th>Saat</th><th>Adet</th></tr></thead>
    <tbody>
    <?php if (!empty($byHour)): ?>
        <?php foreach ($byHour as $h): ?>
            <tr>
                <td><?= htmlspecialchars((string)$h['hour_label']) ?></td>
                <td><?= (int)$h['cnt'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="2">Kayıt yok.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<h3 class="mt-20">Hareketler (son 100)</h3>
<table role="grid">
    <thead>
    <tr>
        <th>ID</th>
        <th>Giriş</th>
        <th>Çıkış</th>
        <th>Yön</th>
        <th>Plaka</th>
        <th>Kişi</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars((string)($r['entry_time'] ?? '')) ?></td>
                <td><?= htmlspecialchars((string)($r['exit_time'] ?? '')) ?></td>
                <td>
                    <?php
                    if (isset($r['direction_label'])) {
                        echo htmlspecialchars((string)$r['direction_label']);
                    } else {
                        if (($r['direction'] ?? '') === 'in') {
                            echo empty($r['exit_time']) ? 'Giriş' : 'Giriş/Çıkış';
                        } else {
                            echo 'Çıkış';
                        }
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars((string)($r['plate_tr'] ?? '')) ?></td>
                <td><?= htmlspecialchars((string)($r['full_name'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">Seçilen aralıkta kayıt yok.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
