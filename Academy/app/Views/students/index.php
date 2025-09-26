<article>
    <header style="display:flex;justify-content:space-between;align-items:center;">
        <h3>Öğrenciler</h3>
        <a role="button" href="/index.php?r=students/create">Yeni Öğrenci</a>
    </header>

    <table>
        <thead>
        <tr><th>ID</th><th>Ad</th><th>Soyad</th><th>E-posta</th><th>İşlemler</th></tr>
        </thead>
        <tbody>
        <?php foreach (($students ?? []) as $s): ?>
            <tr>
                <td><?= (int)$s['id'] ?></td>
                <td><?= htmlspecialchars($s['first_name']) ?></td>
                <td><?= htmlspecialchars($s['last_name']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td>
                    <a href="/index.php?r=students/show&id=<?= (int)$s['id'] ?>">Göster</a>
                    <a href="/index.php?r=students/edit&id=<?= (int)$s['id'] ?>">Düzenle</a>
                    <form action="/index.php?r=students/delete&id=<?= (int)$s['id'] ?>" method="post" style="display:inline" onsubmit="return confirm('Silinsin mi?');">
                        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                        <button type="submit" class="secondary">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</article>
