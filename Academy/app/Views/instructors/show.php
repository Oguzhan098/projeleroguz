<article>
    <h3>Eğitmen Detayı</h3>
    <ul>
        <li><strong>ID:</strong> <?= (int)($instructor['id'] ?? 0) ?></li>
        <li><strong>Ad:</strong> <?= htmlspecialchars($instructor['first_name'] ?? '') ?></li>
        <li><strong>Soyad:</strong> <?= htmlspecialchars($instructor['last_name'] ?? '') ?></li>
        <li><strong>E-posta:</strong> <?= htmlspecialchars($instructor['email'] ?? '') ?></li>
    </ul>
    <footer style="display:flex;gap:.5rem;">
        <a role="button" href="/index.php?r=instructors/edit&id=<?= (int)($instructor['id'] ?? 0) ?>">Düzenle</a>
        <a role="button" class="secondary" href="/index.php?r=instructors/index">Listeye Dön</a>
    </footer>
</article>
