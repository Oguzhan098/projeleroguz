<article>
    <h3>Ders Düzenle #<?= (int)($course['id'] ?? 0) ?></h3>
    <form action="/index.php?r=courses/update&id=<?= (int)($course['id'] ?? 0) ?>" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Güncelle</button>
        <a role="button" href="/index.php?r=courses/show&id=<?= (int)($course['id'] ?? 0) ?>" class="secondary">İptal</a>
    </form>
</article>
