<article>
    <h3>Veli Detayı</h3>
    <ul>
        <li><strong>ID:</strong> <?= (int)($custodians['id'] ?? 0) ?></li>
        <li><strong>Ad:</strong> <?= htmlspecialchars($custodians['first_name'] ?? '') ?></li>
        <li><strong>Soyad:</strong> <?= htmlspecialchars($custodians['last_name'] ?? '') ?></li>
        <li><strong>Öğrenci:</strong> <?= htmlspecialchars(($custodians['student_id'] ?? 0)) ?></li>
    </ul>
    <footer style="display:flex;gap:.5rem;">
        <a role="button" href="/index.php?r=custodians/edit&id=<?= (int)$custodians['id'] ?>">Düzenle</a>
        <a role="button" class="secondary" href="/index.php?r=custodians/index">Listeye Dön</a>
    </footer>
</article>
