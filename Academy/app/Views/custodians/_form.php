<?php
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);

$first = $old['first_name'] ?? ($custodians['first_name'] ?? '');
$last  = $old['last_name']  ?? ($custodians['last_name']  ?? '');
$isd   = $old['student_id'] ?? ($custodian['student_id'] ?? 0);
?>
<label>
    Ad
    <input name="first_name" value="<?= htmlspecialchars($first) ?>"/>
    <?php if (!empty($errors['first_name'])): ?><small style="color:#e74c3c;"><?= htmlspecialchars($errors['first_name']) ?></small><?php endif; ?>
</label>


<label>Soyad
    <input name="last_name" value="<?= htmlspecialchars($last) ?>"/>
    <?php if (!empty($errors['last_name'])): ?>
        <small style="color:#e74c3c;"><?= htmlspecialchars($errors['last_name']) ?></small>
    <?php endif; ?>
</label>


<label>Öğrenci
    <select name="student_id">
        <option value="0">Seçilmedi</option>
        <?php foreach (($students ?? []) as $i): ?>
            <option value="<?= $i['id'] ?>" <?= ($isd == $i['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($i['first_name'].' '.$i['last_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</label>