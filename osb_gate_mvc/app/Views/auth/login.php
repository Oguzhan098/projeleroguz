<h2>Giriş Yap</h2>

<?php if (!empty($flash) && ($flash['type'] ?? '') === 'error'): ?>
    <article class="contrast" style="padding:8px 12px; margin-bottom:12px;">
        <?= htmlspecialchars($flash['message'] ?? 'Bir hata oluştu.') ?>
    </article>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars(\App\Core\Url::to('/login')) ?>" style="max-width:420px;">
    <label>
        Kullanıcı ID
        <input type="text" name="id" placeholder="oguzhan" required autofocus>
    </label>

    <label>
        Şifre
        <input type="password" name="password" placeholder="••••••••" required>
    </label>

    <button type="submit">Giriş</button>
</form>
