<h2>Manuel Hareket</h2>

<form id="movementForm" method="post" action="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">
    <div class="grid">
        <label>
            Plaka (TR format)
            <input
                    type="text"
                    name="plate_tr"
                    id="plate_tr"
                    placeholder="34 ABC 123"
                    inputmode="text"
                    autocomplete="off"
                    required
                    pattern="^(0[1-9]|[1-7][0-9]|8[01])\s?[A-Za-zÇĞİÖŞÜçğıöşü]{1,3}\s?\d{2,4}$"
                    title="TR plaka formatı: 01..81 + 1-3 harf + 2-4 rakam (örn: 34 ABC 123)">
        </label>

        <input type="hidden" name="plate_intl" id="plate_intl" value="">

        <label>
            Kişi (Ad Soyad)
            <input type="text" name="person" placeholder="Ad Soyad" value="<?= htmlspecialchars($_POST['person'] ?? '') ?>">
        </label>

        <label>
            Yön
            <select name="direction" id="direction">
                <option value="in"  <?= (($_POST['direction'] ?? '') === 'out' ? '' : 'selected') ?>>Giriş</option>
                <option value="out" <?= (($_POST['direction'] ?? '') === 'out' ? 'selected' : '') ?>>Çıkış</option>
            </select>
        </label>
    </div>

    <fieldset id="entryTimeBox">
        <legend>Giriş Saati</legend>
        <div class="grid">
            <label>
                Saat
                <select name="entry_hour" id="entry_hour">
                    <?php for ($h=0; $h<=23; $h++): ?>
                        <option value="<?= $h ?>"><?= str_pad((string)$h, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endfor; ?>
                </select>
            </label>
            <label>
                Dakika
                <select name="entry_min" id="entry_min">
                    <?php foreach ([0,5,10,15,20,25,30,35,40,45,50,55] as $m): ?>
                        <option value="<?= $m ?>"><?= str_pad((string)$m, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
    </fieldset>

    <fieldset id="exitTimeBox">
        <legend>Çıkış Saati</legend>
        <div class="grid">
            <label>
                Saat
                <select name="exit_hour" id="exit_hour">
                    <?php for ($h=0; $h<=23; $h++): ?>
                        <option value="<?= $h ?>"><?= str_pad((string)$h, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endfor; ?>
                </select>
            </label>
            <label>
                Dakika
                <select name="exit_min" id="exit_min">
                    <?php foreach ([0,5,10,15,20,25,30,35,40,45,50,55] as $m): ?>
                        <option value="<?= $m ?>"><?= str_pad((string)$m, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
    </fieldset>

    <label style="margin-top:8px; display:inline-flex; align-items:center; gap:6px;">
        <input type="checkbox" name="auto_exit" id="auto_exit" value="1" <?= !empty($_POST['auto_exit']) ? 'checked' : '' ?>>
        Girişten sonra çıkışı otomatik işaretle
    </label>

    <div class="mt-20">
        <button type="submit">Kaydet</button>
        <a class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Listeye Dön</a>
    </div>
</form>

<!-- Sayfaya özel JS -->
<script src="<?= htmlspecialchars(\App\Core\Url::asset('assets/js/script.js')) ?>"></script>
