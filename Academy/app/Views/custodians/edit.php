<article>
    <h3>Veli Düzenle #<?= (int)($custodians['id'] ?? 0); ?></h3>
    <input name="first_name" value="<?= htmlspecialchars($custodians['first_name'] ?? '') ?>">
    <form action="/index.php?r=custodians/update&id=<?= (int)$custodians['id']; ?>" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Güncelle</button>
        <a role="button" class="secondary" href="/index.php?r=custodians/show&id=<?= (int)$custodians['id']; ?>">İptal</a>
    </form>
</article>
