<article>
    <h3>Yeni Öğrenci</h3>
    <form action="/index.php?r=students/store" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Kaydet</button>
        <a role="button" class="secondary" href="/index.php?r=students/index">İptal</a>
    </form>
</article>
