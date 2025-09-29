<?php
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);

$first = $old['first_name'] ?? ($instructor['first_name'] ?? '');
$last  = $old['last_name']  ?? ($instructor['last_name']  ?? '');
$email = $old['email']      ?? ($instructor['email']      ?? '');
?>
<label>Ad
    <input name="first_name" value="<?= htmlspecialchars($first) ?>">
    <?php if (!empty($errors['first_name'])): ?><small style="color:#e74c3c"><?= htmlspecialchars($errors['first_name']) ?></small><?php endif; ?>
</label>
<label>Soyad
    <input name="last_name" value="<?= htmlspecialchars($last) ?>">
    <?php if (!empty($errors['last_name'])): ?><small style="color:#e74c3c"><?= htmlspecialchars($errors['last_name']) ?></small><?php endif; ?>
</label>
<label>E-posta
    <input name="email" value="<?= htmlspecialchars($email) ?>">
    <?php if (!empty($errors['email'])): ?><small style="color:#e74c3c"><?= htmlspecialchars($errors['email']) ?></small><?php endif; ?>
</label>
