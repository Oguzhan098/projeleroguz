<h2>Gösterge Paneli</h2>

<article>
    <header><strong>Özet</strong></header>
    <div class="grid">
        <div>
            <h3>İçeride</h3>
            <p class="stat"><?= (int)($inside ?? 0) ?></p>
        </div>
        <div>
            <h3>Bugünkü Hareket</h3>
            <p class="stat"><?= (int)($today ?? 0) ?></p>
        </div>
    </div>
</article>

<p class="mt-20">
    <a role="button" href="<?= htmlspecialchars(\App\Core\Url::to('/movements')) ?>">Hareket Listesi</a>
    <a role="button" class="secondary" href="<?= htmlspecialchars(\App\Core\Url::to('/movements/create')) ?>">Yeni Kayıt</a>
    <a role="button" class="contrast" href="<?= htmlspecialchars(\App\Core\Url::to('/reports/daily')) ?>">Günlük Rapor</a>
</p>
