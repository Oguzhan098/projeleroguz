<article>
    <h3>Veli Detayı</h3>
    <ul>
        <li><strong>ID:</strong> <?= (int)($custodian['id'] ?? 0) ?></li>
        <li><strong>Ad:</strong> <?= htmlspecialchars($custodian['first_name'] ?? '') ?></li>
        <li><strong>Soyad:</strong> <?= htmlspecialchars($custodian['last_name'] ?? '') ?></li>
        <li><strong>E-posta:</strong> <?= htmlspecialchars($custodian['email'] ?? '') ?></li>
        <li><strong>Kayıt Tarihi:</strong> <?= htmlspecialchars((string)$custodian['registered_at']) ?></li>
    </ul>
    <footer style="display:flex;gap:.5rem;">
        <a role="button" href="/index.php?r=custodians/edit&id=<?= (int)$custodian['id'] ?>">Düzenle</a>
        <a role="button" class="secondary" href="/index.php?r=custodians/index">Listeye Dön</a>
    </footer>
</article>
