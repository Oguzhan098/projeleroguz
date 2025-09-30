<?php
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);

$code  = $old['code'] ?? ($course['code'] ?? '');
$title = $old['title'] ?? ($course['title'] ?? '');
$desc  = $old['description'] ?? ($course['description'] ?? '');
$iid   = $old['instructor_id'] ?? ($course['instructor_id'] ?? 0);
?>
<label>Kod
    <input name="code" value="<?= htmlspecialchars($code) ?>">
    <?php if (!empty($errors['code'])): ?><small style="color:#e74c3c"><?= $errors['code'] ?></small><?php endif; ?>
</label>
<label>Başlık
    <input name="title" value="<?= htmlspecialchars($title) ?>">
    <?php if (!empty($errors['title'])): ?><small style="color:#e74c3c"><?= $errors['title'] ?></small><?php endif; ?>
</label>
<label>Açıklama
    <textarea name="description"><?= htmlspecialchars($desc) ?></textarea>
</label>


<label>Eğitmen
    <select name="instructor_id">
        <option value="0">Seçilmedi</option>
        <?php foreach (($instructors ?? []) as $i): ?>
            <option value="<?= $i['id'] ?>" <?= ($iid == $i['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($i['first_name'].' '.$i['last_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</label>
