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
            <select name="direction" id="direction">
                <option value="in"  <?= (($_POST['direction'] ?? '') === 'out' ? '' : 'selected') ?>>Giriş</option>
                <option value="out" <?= (($_POST['direction'] ?? '') === 'out' ? 'selected' : '') ?>>Çıkış</option>
            </select>
        </label>
    </div>

    <!-- GİRİŞ SAATİ -->
    <fieldset id="entryTimeBox">
        <legend>Giriş Saati</legend>
        <div class="grid">
            <label>
                Saat
                <select name="entry_hour" id="entry_hour">
                    <?php for ($h=0; $h<=23; $h++): ?>
                        <option value="<?= $h ?>" <?= ((string)($h) === (string)($_POST['entry_hour'] ?? '')) ? 'selected':'' ?>>
                            <?= str_pad((string)$h, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </label>

            <label>
                Dakika
                <select name="entry_min" id="entry_min">
                    <?php foreach ([0,5,10,15,20,25,30,35,40,45,50,55] as $m): ?>
                        <option value="<?= $m ?>" <?= ((string)($m) === (string)($_POST['entry_min'] ?? '')) ? 'selected':'' ?>>
                            <?= str_pad((string)$m, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <small>Varsayılan: şu anki saate en yakın dakika dilimini seç.</small>
    </fieldset>

    <!-- ÇIKIŞ SAATİ -->
    <fieldset id="exitTimeBox">
        <legend>Çıkış Saati</legend>
        <div class="grid">
            <label>
                Saat
                <select name="exit_hour" id="exit_hour">
                    <?php for ($h=0; $h<=23; $h++): ?>
                        <option value="<?= $h ?>" <?= ((string)($h) === (string)($_POST['exit_hour'] ?? '')) ? 'selected':'' ?>>
                            <?= str_pad((string)$h, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </label>

            <label>
                Dakika
                <select name="exit_min" id="exit_min">
                    <?php foreach ([0,5,10,15,20,25,30,35,40,45,50,55] as $m): ?>
                        <option value="<?= $m ?>" <?= ((string)($m) === (string)($_POST['exit_min'] ?? '')) ? 'selected':'' ?>>
                            <?= str_pad((string)$m, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <small>Yön=Çıkış ise zorunlu; Yön=Giriş ve “otomatik kapat” işaretliyse istersen belirleyebilirsin.</small>
    </fieldset>

    <!-- Girişten sonra otomatik çıkış kapatma -->
    <label style="margin-top:8px; display:inline-flex; align-items:center; gap:6px;">
        <input type="checkbox" name="auto_exit" id="auto_exit" value="1" <?= !empty($_POST['auto_exit']) ? 'checked' : '' ?>>
        Girişten sonra çıkışı otomatik işaretle
    </label>

    <div class="mt-20">
        <button type="submit">Kaydet</button>
        <a class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Listeye Dön</a>
    </div>
</form>

<script>
    (function(){
        const dirSel = document.getElementById('direction');
        const autoExit = document.getElementById('auto_exit');
        const entryBox = document.getElementById('entryTimeBox');
        const exitBox  = document.getElementById('exitTimeBox');

        function refreshVisibility(){
            const dir = dirSel.value;
            // Giriş: giriş saatini göster, çıkışı sadece auto_exit varsa göster
            if (dir === 'in') {
                entryBox.style.display = '';
                exitBox.style.display  = (autoExit && autoExit.checked) ? '' : 'none';
            } else {
                // Çıkış: giriş saati gizli, çıkış saati görünür
                entryBox.style.display = 'none';
                exitBox.style.display  = '';
            }
        }

        dirSel.addEventListener('change', refreshVisibility);
        if (autoExit) autoExit.addEventListener('change', refreshVisibility);
        refreshVisibility();

        // Formu açarken şu anki saate yakın default değerleri set etmek istersen:
        try {
            const now = new Date();
            const round5 = m => Math.round(m/5)*5 % 60;
            const setIfEmpty = (id, val) => { const el = document.getElementById(id); if (el && !el.value) el.value = String(val); };
            setIfEmpty('entry_hour', now.getHours());
            setIfEmpty('entry_min',  round5(now.getMinutes()));
            setIfEmpty('exit_hour',  now.getHours());
            setIfEmpty('exit_min',   round5(now.getMinutes()));
        } catch(e){}
    })();
</script>
