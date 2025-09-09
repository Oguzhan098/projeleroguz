<?php

$planes = $planes ?? [];
$airports = $airports ?? [];
$flights = $flights ?? [];

usort($flights, function($a, $b) {
    return $b['id'] - $a['id'];
});
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Uçuş Ekle</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="assets/js/flight_time.js" defer></script>
</head>
<body>

<h1>Yeni Uçuş Ekle</h1>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="msg success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="msg error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="action" value="add_flight">

    <div class="form-columns">
        <div>
            <h3>Yolcu Bilgileri</h3>
            İsim: <input type="text" name="first_name" required>
            Soyisim: <input type="text" name="last_name" required>
            Yaş: <input type="number" name="age" min="0" required>
            Cinsiyet:
            <select name="gender" required>
                <option value="">Seçiniz</option>
                <option value="Erkek">Erkek</option>
                <option value="Kadın">Kadın</option>
            </select>
        </div>

        <div>
            <h3>Uçuş Bilgileri</h3>
            Kalkış: <input type="text" name="departure" required>
            Varış: <input type="text" name="arrival" required>
            Kalkış Tarihi: <input type="date" name="departure_date" id="departure_date" required>
            Kalkış Saati: <input type="time" name="departure_time" id="departure_time" required>
            Varış Tarihi: <input type="date" name="arrival_date" id="arrival_date" required>
            Varış Saati: <input type="time" name="arrival_time" id="arrival_time" required>
            Yolcu Sayısı: <input type="number" name="passenger_count" value="1" min="1">

            Uçak:
            <select name="plane_id" required>
                <option value="">Seçiniz</option>
                <?php foreach($planes as $plane): ?>
                    <option value="<?= htmlspecialchars($plane['id']) ?>"><?= htmlspecialchars($plane['brand'] . ' ' . $plane['model']) ?></option>
                <?php endforeach; ?>
            </select>

            Havalimanı:
            <select name="airport_id" required>
                <option value="">Seçiniz</option>
                <?php foreach($airports as $airport): ?>
                    <option value="<?= htmlspecialchars($airport['id']) ?>"><?= htmlspecialchars($airport['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit">Uçuş Ekle</button>
</form>

<div class="table-container">
    <h2 style="text-align:center;">Uçuş Listesi</h2>
    <table border="1" cellpadding="6" cellspacing="0">
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
            <th>İşlem</th>
        </tr>

        <?php if(!empty($flights)): ?>
            <?php foreach($flights as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['id']) ?></td>
                    <td><?= htmlspecialchars($f['first_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['last_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['age'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['gender'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['departure'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['arrival'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['departure_date'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['departure_time'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['arrival_date'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['arrival_time'] ?? '-') ?></td>
                    <td><?= htmlspecialchars(($f['brand'] ?? '') . ' ' . ($f['model'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($f['airport_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($f['passenger_count'] ?? 1) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Bu uçuşu silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="action" value="delete_flight">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($f['id']) ?>">
                            <button type="submit">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="15" style="text-align:center;">Uçuş bulunamadı.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if (!empty($flights)): ?>
        <form method="POST" onsubmit="return confirm('Tüm uçuşları silmek istediğinize emin misiniz?');">
            <input type="hidden" name="action" value="clear_flights">
            <button type="submit">Temizle (Tümünü Sil)</button>
        </form>
    <?php endif; ?>
</div>

<script src="/assets/js/flight_time.js" defer></script>

</body>
</html>
