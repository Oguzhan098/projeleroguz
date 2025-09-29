<article>
    <h3>Öğrenciyi Derse Kaydet</h3>
    <form action="/index.php?r=enrollments/store" method="post">
        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
        <label>Öğrenci
            <select name="student_id" required>
                <option value="">Seçiniz</option>
                <?php foreach (($students ?? []) as $s): ?>
                    <option value="<?= (int)$s['id'] ?>">
                        <?= htmlspecialchars($s['first_name'].' '.$s['last_name'].' ('.$s['email'].')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Ders
            <select name="course_id" required>
                <option value="">Seçiniz</option>
                <?php foreach (($courses ?? []) as $c): ?>
                    <option value="<?= (int)$c['id'] ?>">
                        <?= htmlspecialchars(($c['code'] ?? '').' - '.$c['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Not (opsiyonel)
            <input type="number" name="grade" step="0.01" min="0" max="100" placeholder="örn: 95.50">
        </label>

        <button type="submit">Kaydet</button>
        <a role="button" class="secondary" href="/index.php?r=enrollments/index">İptal</a>
    </form>
</article>
