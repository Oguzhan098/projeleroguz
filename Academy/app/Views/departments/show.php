<?php include VIEW_PATH . '/partials/header.php'; ?>

<h1>Departman Detayı</h1>

<?php if (!empty($department)): ?>
    <dl>
        <dt>ID</dt><dd><?= (int)$department['id'] ?></dd>
        <dt>Kod</dt><dd><?= htmlspecialchars($department['code']) ?></dd>
        <dt>Ad</dt><dd><?= htmlspecialchars($department['name']) ?></dd>
        <dt>Açıklama</dt><dd><?= htmlspecialchars($department['description'] ?? '') ?></dd>
        <dt>Oluşturma</dt><dd><?= htmlspecialchars($department['created_at']) ?></dd>
        <dt>Güncelleme</dt><dd><?= htmlspecialchars($department['updated_at']) ?></dd>
    </dl>

    <p>
        <a href="/index.php?r=departments/edit&id=<?= (int)$department['id'] ?>">Düzenle</a> |
        <a href="/index.php?r=departments/index">Listeye Dön</a>
    </p>

    <hr>
    <h2>İlişkilendir</h2>


    <form method="post"
          action="/index.php?r=departments/linkStudent"
          style="display:inline-block;margin:4px 8px;min-width:360px">

        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <input type="hidden" name="id" value="<?= (int)$department['id'] ?>">


        <select name="student_id" required style="width:100%;">
            <option value="">Öğrenci seçin...</option>

            <?php foreach (($studentOptions ?? []) as $s): ?>
                <?php
                $label = '#'.(int)$s['id'].' — '.htmlspecialchars($s['first_name'].' '.$s['last_name']).' ('.htmlspecialchars($s['email']).')';
                if (!empty($s['department_id']) && (int)$s['department_id'] === (int)$department['id']) {
                    $label .= ' ✓ (bu departman)';
                }
                ?>
                <option value="<?= (int)$s['id'] ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>


        <button type="submit" style="width:100%;margin-top:8px;">Öğrenci Bağla</button>
    </form>


    <form method="post" action="/index.php?r=departments/linkInstructor" style="display:inline-block;margin:12px 8px;min-width:360px">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <input type="hidden" name="id" value="<?= (int)$department['id'] ?>">

        <select name="instructor_id" required style="width:100%;">
            <option value="">Eğitmen seçin...</option>
            <?php foreach (($instructorOptions ?? []) as $t): ?>
                <?php
                $label = '#'.(int)$t['id'].' — '.htmlspecialchars($t['first_name'].' '.$t['last_name']).' ('.htmlspecialchars($t['email']).')';
                if (!empty($t['department_id']) && (int)$t['department_id'] === (int)$department['id']) {
                    $label .= ' ✓ (bu departman)';
                }
                ?>
                <option value="<?= (int)$t['id'] ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="width:100%;margin-top:8px;">Eğitmen Bağla</button>
    </form>

    <h3 style="margin-top:24px;">Bu Departmana Bağlı Öğrenciler</h3>
    <?php if (!empty($students)): ?>
        <ul>
            <?php foreach ($students as $s): ?>
                <li>#<?= (int)$s['id'] ?> — <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?> (<?= htmlspecialchars($s['email']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?><p>Öğrenci yok.</p><?php endif; ?>


    <h3>Eğitmenler</h3>
    <?php if (!empty($instructors)): ?>
        <ul>
            <?php foreach ($instructors as $t): ?>
                <li>#<?= (int)$t['id'] ?> — <?= htmlspecialchars($t['first_name'].' '.$t['last_name']) ?> (<?= htmlspecialchars($t['email']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?><p>Eğitmen yok.</p><?php endif; ?>

<?php else: ?>


    <p>Kayıt bulunamadı.</p>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
