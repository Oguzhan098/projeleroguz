
<?php use App\Core\Url; use App\Core\Helpers as H; ?>

<article>
    <header style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h3>Başarılar</h3>

        <form method="get" action="/index.php" style="margin-bottom:10px">
            <input type="hidden" name="r" value="achievements/index">
            <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Ara (ad/soyad)">
            <button type="submit">Ara</button>
        </form>

<?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
        <div class="flash <?= htmlspecialchars($type) ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endforeach; unset($_SESSION['flash']); ?>
<?php endif; ?>

        <p>Toplam: <b><?= (int)$total ?></b> kayıt</p>

        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Kayıt Zamanı</th>
                <th>İşlemler</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($achievements as $a): ?>
            <tr>
                <td><?= (int)$a['id'] ?></td>
                <td><?= htmlspecialchars($a['first_name']) ?></td>
                <td><?= htmlspecialchars($a['last_name']) ?></td>
                <td><?= htmlspecialchars($a['registered_at'] ?? '') ?></td>
                <td>
                    <a href="/index.php?r=achievements/show&id=<?= (int)$a['id'] ?>">Göster</a>
                    <a href="/index.php?r=achievements/edit&id=<?= (int)$a['id'] ?>">Düzenle</a>
                    <form method="post"
                          action="/index.php?r=achievements/delete&id=<?= (int)$a['id'] ?>"
                          style="display:inline"
                          onsubmit="return confirm('Silinsin mi?')">
                        <button type="submit">Sil</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (($pages ?? 1) > 1): ?>
            <nav style="margin-top:10px">
                <?php for ($p = 1; $p <= $pages; $p++): ?>
                    <a href="/index.php?r=achievements/index&page=<?= $p ?>&q=<?= urlencode($q ?? '') ?>"<?= $p===$page ? ' style="font-weight:bold"' : '' ?>><?= $p ?></a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>

        <p style="margin-top:10px">
            <a href="/index.php?r=achievements/create">+ Yeni Öğrenci</a>
        </p>


