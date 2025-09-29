<article>
    <header style="display:flex;justify-content:space-between;">
        <h3>Dersler</h3>
        <a role="button" href="/index.php?r=courses/create">Yeni Ders</a>
    </header>

    <table>
        <thead>
        <tr><th>Kod</th><th>Başlık</th><th>Eğitmen</th><th>İşlemler</th></tr>
        </thead>
        <tbody>
        <?php foreach (($courses ?? []) as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['code']) ?></td>
                <td><?= htmlspecialchars($c['title']) ?></td>
                <td><?= htmlspecialchars(($c['instructor_first'] ?? '') . ' ' . ($c['instructor_last'] ?? '')) ?></td>
                <td>
                    <a href="/index.php?r=courses/show&id=<?= (int)$c['id'] ?>">Göster</a>
                    <a href="/index.php?r=courses/edit&id=<?= (int)$c['id'] ?>">Düzenle</a>
                    <form action="/index.php?r=courses/delete&id=<?= (int)$c['id'] ?>" method="post" style="display:inline" onsubmit="return confirm('Silinsin mi?');">
                        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                        <button type="submit" class="secondary">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</article>
