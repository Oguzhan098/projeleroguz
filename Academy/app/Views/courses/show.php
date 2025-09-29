<article>
    <h3>Ders Detayı</h3>
    <ul>
        <li><strong>Kod:</strong> <?= htmlspecialchars($course['code'] ?? '') ?></li>
        <li><strong>Başlık:</strong> <?= htmlspecialchars($course['title'] ?? '') ?></li>
        <li><strong>Açıklama:</strong> <?= htmlspecialchars($course['description'] ?? '') ?></li>
        <li><strong>Eğitmen:</strong> <?= htmlspecialchars(($course['instructor_id'] ?? 0)) ?></li>
    </ul>
    <footer style="display:flex;gap:.5rem;">
        <a role="button" href="/index.php?r=courses/edit&id=<?= (int)($course['id'] ?? 0) ?>">Düzenle</a>
        <a role="button" class="secondary" href="/index.php?r=courses/index">Listeye Dön</a>
    </footer>
</article>
