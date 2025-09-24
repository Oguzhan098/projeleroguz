<h2>Manuel Hareket</h2>

<form method="post" action="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">
    <div class="grid">
        <label>
            Plaka
            <input type="text" name="plate" placeholder="34 ABC 123" value="<?= htmlspecialchars($_POST['plate'] ?? '') ?>">
        </label>
        <label>
            Kişi (Ad Soyad)
            <input type="text" name="person" placeholder="Ad Soyad" value="<?= htmlspecialchars($_POST['person'] ?? '') ?>">
        </label>
        <label>
            Yön
            <select name="direction">
                <option value="in"  <?= (($_POST['direction'] ?? '') === 'in'  ? 'selected' : '') ?>>Giriş</option>
                <option value="out" <?= (($_POST['direction'] ?? '') === 'out' ? 'selected' : '') ?>>Çıkış</option>
            </select>
        </label>
    </div>

    <!-- Opsiyonel: Girişten sonra çıkışı otomatik kapat -->
    <label style="margin-top:8px; display:inline-flex; align-items:center; gap:6px;">
        <input type="checkbox" name="auto_exit" value="1" <?= !empty($_POST['auto_exit']) ? 'checked' : '' ?>>
        Girişten sonra çıkışı otomatik işaretle
    </label>

    <div class="mt-20">
        <button type="submit">Kaydet</button>
        <a class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Listeye Dön</a>
    </div>
</form>
