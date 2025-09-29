<article>
    <h3>Yeni Ders</h3>
    <form action="/index.php?r=courses/store" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Kaydet</button>
        <a role="button" href="/index.php?r=courses/index" class="secondary">İptal</a>
    </form>
</article>
