<?php
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Uçuşlar</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<h1>Uçuş Listesi</h1>
<table border="1">
    <tr>
        <th>Nereden</th>
        <th>Nereye</th>
        <th>Yolcu Sayısı</th>
        <th>Başlangıç</th>
        <th>Bitiş</th>
    </tr>
    <?php foreach ($flights as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['nereden']) ?></td>
            <td><?= htmlspecialchars($f['nereye']) ?></td>
            <td><?= $f['yolcu_sayisi'] ?></td>
            <td><?= $f['baslangic_zamani'] ?></td>
            <td><?= $f['bitis_zamani'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
