<?php use App\Core\Url;
use App\Core\Helpers as H; ?>
<article>
    <header style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h3>Veliler</h3>
        <form method="get" action="/index.php" style="display:flex;gap:.5rem;">
            <input type="hidden" name="r" value="custodians/index">
            <input type="search" name="q" placeholder="ad, soyad" value="<?= H::e($q ?? '') ?>">
            <button type="submit" class="secondary">Ara</button>
            <a role="button" href="<?= Url::to('custodians/index') ?>" class="secondary">Sıfırla</a>
            <a role="button" href="<?= Url::to('custodians/create') ?>">Yeni Öğrenci</a>
        </form>
    </header>

    <small><?= (int)($total ?? 0) ?> kayıt bulundu</small>

    <table>
        <thead>
        <tr><th>ID</th><th>Ad</th><th>Soyad</th><th>Öğrenci</th></tr>
        </thead>
        <tbody>
        <?php foreach (($custodians ?? []) as $s): ?>
            <tr>
                <td><?= (int)$s['id'] ?></td>
                <td><?= H::e($s['first_name']) ?></td>
                <td><?= H::e($s['last_name']) ?></td>
                <td><?= htmlspecialchars(($c['student_first'] ?? '') . ' ' . ($c['student_last'] ?? '')) ?></td>
                <td>
                    <a href="<?= Url::to('custodians/show', ['id'=>$s['id']]) ?>">Göster</a>
                    <a href="<?= Url::to('custodians/edit', ['id'=>$s['id']]) ?>">Düzenle</a>
                    <form action="<?= Url::to('custodians/delete', ['id'=>$s['id']]) ?>" method="post" style="display:inline" onsubmit="return confirm('Silinsin mi?');">
                        <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                        <button type="submit" class="secondary">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (($pages ?? 1) > 1): ?>
        <nav aria-label="Sayfalama" style="display:flex;gap:.4rem;justify-content:flex-end;">
            <?php for ($p=1; $p<=($pages ?? 1); $p++): ?>
                <?php $href = Url::to('custodians/index', array_filter(['page'=>$p,'q'=>$q ?? ''], fn($v)=>$v!=='')); ?>
                <a class="secondary" href="<?= $href ?>" <?= ($p==($page ?? 1)) ? 'aria-current="page"' : '' ?>><?= $p ?></a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</article>
