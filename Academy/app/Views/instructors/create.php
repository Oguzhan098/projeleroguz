<?php include VIEW_PATH . '/partials/header.php'; ?>

<h1>Yeni Eğitmen</h1>

<?php

$errors = $errors ?? ($_SESSION['errors'] ?? []);
$old    = $old    ?? ($_SESSION['old']    ?? []);
unset($_SESSION['errors'], $_SESSION['old']);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0;">
            <?php foreach ($errors as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="/index.php?r=instructors/store">
    <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">

    <label>Ad</label>
    <input type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required>

    <label>Soyad</label>
    <input type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required>

    <label>E-posta</label>
    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>

    <button type="submit">Kaydet</button>
    <a href="/index.php?r=instructors/index">Vazgeç</a>
</form>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
