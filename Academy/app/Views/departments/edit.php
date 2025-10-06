<?php include VIEW_PATH . '/partials/header.php'; ?>
<?php
$errors = $errors ?? ($_SESSION['errors'] ?? []);
$old    = $old    ?? ($_SESSION['old']    ?? []);
unset($_SESSION['errors'], $_SESSION['old']);
?>
<h1>Departman Düzenle</h1>

<?php if (!empty($errors)): ?>
    <div class="alert error"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<?php if (!empty($department)): ?>

    <form method="post" action="/index.php?r=departments/update&id=<?= (int)$department['id'] ?>">

        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">

        <label>Kod</label>
        <input type="text" name="code" value="<?= htmlspecialchars(strtoupper($old['code'] ?? $department['code'])) ?>" maxlength="20" required>

        <label>Ad</label>
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $department['name']) ?>" maxlength="150" required>

        <label>Açıklama</label>
        <input type="text" name="description" value="<?= htmlspecialchars($old['description'] ?? ($department['description'] ?? '')) ?>">

        <button type="submit">Güncelle</button>
        <a href="/index.php?r=departments/show&id=<?= (int)$department['id'] ?>">İptal</a>
    </form>

<?php else: ?>
    <p>Kayıt bulunamadı.</p>
<?php endif; ?>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
