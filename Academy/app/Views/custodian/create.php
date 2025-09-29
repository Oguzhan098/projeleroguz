<article>
    <h3>Yeni Veli</h3>
    <form action="/index.php?r=custodians/store" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Kaydet</button>
        <a role="button" class="secondary" href="/index.php?r=custodians/index">İptal</a>
    </form>
</article>
