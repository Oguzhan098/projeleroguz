<article>
    <header style="display:flex;justify-content:space-between;align-items:center;">
        <h3>Eğitmenler</h3>
        <a role="button" href="/index.php?r=instructors/create">Yeni Eğitmen</a>
    </header>

    <table>
        <thead>
        <tr><th>ID</th><th>Ad</th><th>Soyad</th><th>E-posta</th><th>İşlemler</th></tr>
        </thead>
        <tbody>
        <?php foreach (($instructors ?? []) as $i): ?>
            <tr>
                <td><?= (int)$i['id'] ?></td>
                <td><?= htmlspecialchars($i['first_name']) ?></td>
                <td><?= htmlspecialchars($i['last_name']) ?></td>
                <td><?= htmlspecialchars($i['email']) ?></td>
                <td>
                    <a href="/index.php?r=instructors/show&id=<?= (int)$i['id'] ?>">Göster</a>
                    <a href="/index.php?r=instructors/edit&id=<?= (int)$i['id'] ?>">Düzenle</a>
                    <form action="/index.php?r=instructors/delete&id=<?= (int)$i['id'] ?>" method="post" style="display:inline" onsubmit="return confirm('Silinsin mi?');">
                        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                        <button type="submit" class="secondary">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</article>
