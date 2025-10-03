<?php include VIEW_PATH . '/partials/header.php'; ?>
<h1>Departmanlar</h1>

<form method="get" action="/index.php" style="margin:10px 0;">
    <input type="hidden" name="r" value="departments/index">
    <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Ara (kod/ad)">
    <button type="submit">Ara</button>
    <a href="/index.php?r=departments/create">+ Yeni</a>
</form>

<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Kod</th>
        <th>Ad</th>
        <th>İlişkiler</th>
        <th>İşlem</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Güvenli fallback (controller 'rels' yollamazsa boş dizi kullan)
    $rels = $rels ?? ['students'=>[], 'students_count'=>[], 'instructors'=>[], 'instructors_count'=>[]];
    ?>
    <?php foreach ($rows as $r): ?>
        <?php
        $depId  = (int)$r['id'];
        $stuList = $rels['students'][$depId] ?? [];
        $stuCnt  = $rels['students_count'][$depId] ?? 0;
        $insList = $rels['instructors'][$depId] ?? [];
        $insCnt  = $rels['instructors_count'][$depId] ?? 0;

        $renderPeople = function(array $list, int $total, int $depId) {
            if (empty($list)) { echo '<em>yok</em>'; return; }
            $shown = 0;
            foreach ($list as $p) {
                $shown++;
                $name = htmlspecialchars($p['first_name'].' '.$p['last_name'], ENT_QUOTES, 'UTF-8');
                echo '#'.(int)$p['id'].' '.$name.'<br>';
            }
            if ($total > $shown) {
                $more = $total - $shown;
                echo '<small>+'. $more .' daha — <a href="/index.php?r=departments/show&id='.(int)$depId.'">tümü</a></small>';
            }
        };
        ?>
        <tr>
            <td><?= $depId ?></td>
            <td><?= htmlspecialchars($r['code']) ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>

            <!-- İlişkiler sütunu -->
            <td>
                <table class="subtable" style="border-collapse:collapse;width:100%;background:#fafafa;">
                    <tr>
                        <th style="text-align:left;white-space:nowrap;padding-right:8px;">Öğrenciler</th>
                        <td><?php $renderPeople($stuList, $stuCnt, $depId); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align:left;white-space:nowrap;padding-right:8px;">Eğitmenler</th>
                        <td><?php $renderPeople($insList, $insCnt, $depId); ?></td>
                    </tr>
                </table>
            </td>

            <!-- İşlem sütunu -->
            <td>
                <a href="/index.php?r=departments/show&id=<?= $depId ?>">Göster</a> |
                <a href="/index.php?r=departments/edit&id=<?= $depId ?>">Düzenle</a> |
                <form method="post" action="/index.php?r=departments/delete&id=<?= $depId ?>" style="display:inline" onsubmit="return confirm('Silinsin mi?');">
                    <input type="hidden" name="csrf" value="<?= \App\Core\Csrf::token() ?>">
                    <button type="submit">Sil</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if (($pages ?? 1) > 1): ?>
    <nav style="margin-top:10px;">
        <?php $qparam = $q ? '&q='.urlencode($q) : ''; $prev = max(1, $page-1); $next = min($pages, $page+1); ?>
        <a href="/index.php?r=departments/index<?= $qparam ?>&page=<?= $prev ?>">« Önceki</a>
        <?php for ($i=1; $i<=$pages; $i++): ?>
            <?= $i == $page ? "<strong style=\"margin:0 4px;\">$i</strong>" : "<a style=\"margin:0 4px;\" href=\"/index.php?r=departments/index$qparam&page=$i\">$i</a>" ?>
        <?php endfor; ?>
        <a href="/index.php?r=departments/index<?= $qparam ?>&page=<?= $next ?>">Sonraki »</a>
    </nav>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
