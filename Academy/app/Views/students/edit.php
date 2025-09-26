<article>
    <h3>Öğrenci Düzenle #<?= (int)($student['id'] ?? 0); ?></h3>
    <input name="first_name" value="<?= htmlspecialchars($student['first_name'] ?? '') ?>">
    <form action="/index.php?r=students/update&id=<?= (int)$student['id']; ?>" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <button type="submit">Güncelle</button>
        <a role="button" class="secondary" href="/index.php?r=students/show&id=<?= (int)$student['id']; ?>">İptal</a>
    </form>
</article>
