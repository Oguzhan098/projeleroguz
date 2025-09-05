<?php
$planes = $planes ?? [];
$airports = $airports ?? [];
$flights = $flights ?? [];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Uçuş Ekle</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h1>Yeni Uçuş Ekle</h1>

<form method="POST">
    <div class="form-columns">
        <div>
            <h3>Yolcu Bilgileri</h3>
            İsim: <input type="text" name="first_name" required>
            Soyisim: <input type="text" name="last_name" required>
            Yaş: <input type="number" name="age" min="0" required>
            Cinsiyet:
            <select name="gender" required>
                <option value="Erkek">Erkek</option>
                <option value="Kadın">Kadın</option>
                <option value="Diğer">Belirtmek İstemiyorum</option>
            </select>
        </div>

        <div>
            <h3>Uçuş Bilgileri</h3>
            Kalkış: <input type="text" name="departure" required>
            Varış: <input type="text" name="arrival" required>
            Kalkış Tarihi: <input type="date" name="departure_date" required>
            Kalkış Saati: <input type="time" name="departure_time" required>
            Varış Tarihi: <input type="date" name="arrival_date" required>
            Varış Saati: <input type="time" name="arrival_time" required>
            Yolcu Sayısı: <input type="number" name="passenger_count" value="1" min="1">

            Uçak:
            <select name="plane_id" required>
                <?php foreach($planes as $plane): ?>
                    <option value="<?= $plane['id'] ?>"><?= htmlspecialchars($plane['brand'] . ' ' . $plane['model']) ?></option>
                <?php endforeach; ?>
            </select>

            Havalimanı:
            <select name="airport_id" required>
                <?php foreach($airports as $airport): ?>
                    <option value="<?= $airport['id'] ?>"><?= htmlspecialchars($airport['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit">Uçuş Ekle</button>
</form>

<div class="table-container">
    <h2>Uçuş Listesi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>İsim</th>
            <th>Soyisim</th>
            <th>Yaş</th>
            <th>Cinsiyet</th>
            <th>Kalkış</th>
            <th>Varış</th>
            <th>Kalkış Tarihi</th>
            <th>Kalkış Saati</th>
            <th>Varış Tarihi</th>
            <th>Varış Saati</th>
            <th>Uçak</th>
            <th>Havalimanı</th>
            <th>Yolcu Sayısı</th>
        </tr>
        <?php if(!empty($flights)): ?>
            <?php foreach($flights as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['id']) ?></td>
                    <td><?= htmlspecialchars($f['first_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['last_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['age'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['gender'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['departure']) ?></td>
                    <td><?= htmlspecialchars($f['arrival']) ?></td>
                    <td><?= htmlspecialchars($f['departure_date']) ?></td>
                    <td><?= htmlspecialchars($f['departure_time']) ?></td>
                    <td><?= htmlspecialchars($f['arrival_date']) ?></td>
                    <td><?= htmlspecialchars($f['arrival_time']) ?></td>
                    <td><?= htmlspecialchars($f['brand'] . ' ' . $f['model']) ?></td>
                    <td><?= htmlspecialchars($f['airport_name']) ?></td>
                    <td><?= htmlspecialchars($f['passenger_count']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="14" style="text-align:center;">Uçuş bulunamadı.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
