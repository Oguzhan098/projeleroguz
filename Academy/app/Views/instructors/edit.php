<article>
    <h3>Eğitmen Düzenle #<?= (int)($instructor['id'] ?? 0) ?></h3>
    <form action="/index.php?r=instructors/update&id=<?= (int)($instructor['id'] ?? 0) ?>" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Güncelle</button>
        <a role="button" class="secondary" href="/index.php?r=instructors/show&id=<?= (int)($instructor['id'] ?? 0) ?>">İptal</a>
    </form>
</article>
