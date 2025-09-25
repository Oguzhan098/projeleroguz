<h2>Son 50 Hareket</h2>

<p>
    <a role="button" href="<?= htmlspecialchars(\App\Core\Url::to('/movements/create')) ?>">Yeni Kayıt</a>
    <a role="button" class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/')) ?>">Gösterge Paneli</a>
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
                <td>
                    <?php
                    // Controller direction_label set ediyor ama yedek olsun:
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
        <tr><td colspan="6">Kayıt yok.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
