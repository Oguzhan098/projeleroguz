<article>
    <h3>Öğrenci Detayı</h3>
    <ul>
        <li><strong>ID:</strong> <?= (int)($student['id'] ?? 0) ?></li>
        <li><strong>Ad:</strong> <?= htmlspecialchars($student['first_name'] ?? '') ?></li>
        <li><strong>Soyad:</strong> <?= htmlspecialchars($student['last_name'] ?? '') ?></li>
        <li><strong>E-posta:</strong> <?= htmlspecialchars($student['email'] ?? '') ?></li>
        <li><strong>Kayıt Tarihi:</strong> <?= htmlspecialchars((string)$student['registered_at']) ?></li>
    </ul>
    <footer style="display:flex;gap:.5rem;">
        <a role="button" href="/index.php?r=students/edit&id=<?= (int)$student['id'] ?>">Düzenle</a>
        <a role="button" class="secondary" href="/index.php?r=students/index">Listeye Dön</a>
    </footer>
</article>
