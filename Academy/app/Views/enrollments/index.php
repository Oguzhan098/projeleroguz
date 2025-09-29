<article>
    <header style="display:flex;justify-content:space-between;align-items:center;">
        <h3>Kayıtlar</h3>
        <a role="button" href="/index.php?r=enrollments/create">Yeni Kayıt</a>
    </header>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Öğrenci</th>
            <th>Ders</th>
            <th>Kod</th>
            <th>Not</th>
            <th>İşlemler</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach (($enrollments ?? []) as $e): ?>
            <tr>
                <td><?= (int)$e['id'] ?></td>
                <td><?= htmlspecialchars(($e['student_first'] ?? '') . ' ' . ($e['student_last'] ?? '')) ?></td>
                <td><?= htmlspecialchars($e['course_title'] ?? '') ?></td>
                <td><?= htmlspecialchars($e['course_code'] ?? '') ?></td>
                <td>
                    <form action="/index.php?r=enrollments/updateGrade&id=<?= (int)$e['id'] ?>" method="post" style="display:inline-flex; gap:.3rem;">
                        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                        <input name="grade" type="number" step="0.01" min="0" max="100"
                               value="<?= htmlspecialchars($e['grade'] ?? '') ?>" style="width:7rem;">
                        <button type="submit" class="secondary">Kaydet</button>
                    </form>
                </td>
                <td>
                    <form action="/index.php?r=enrollments/delete&id=<?= (int)$e['id'] ?>" method="post" onsubmit="return confirm('Kayıt silinsin mi?');" style="display:inline;">
                        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                        <button type="submit" class="secondary">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</article>
