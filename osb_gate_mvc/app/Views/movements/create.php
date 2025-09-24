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
    <button type="submit">Kaydet</button>
    <a class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Listeye Dön</a>
</form>
