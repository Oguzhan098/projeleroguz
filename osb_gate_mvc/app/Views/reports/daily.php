<h2>Günlük Rapor</h2>

<p>
    <a role="button" class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/')) ?>">Gösterge Paneli</a>
    <a role="button" href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Hareket Listesi</a>
</p>

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
                <td><?= htmlspecialchars((string)$r['direction']) ?></td>
                <td><?= htmlspecialchars((string)($r['plate'] ?? '')) ?></td>
                <td><?= htmlspecialchars((string)($r['full_name'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">Bugün için kayıt yok.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
