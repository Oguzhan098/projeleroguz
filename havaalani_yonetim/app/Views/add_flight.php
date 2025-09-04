
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Uçuş Ekle</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<h1>Yeni Uçuş Ekle</h1>

<form method="POST" action="/index.php?action=add">
    <h3>Yolcu Bilgileri</h3>
    <label>Ad:</label>
    <input type="text" name="first_name" required><br>

    <label>Soyad:</label>
    <input type="text" name="last_name" required><br>

    <label>Cinsiyet:</label>
    <select name="gender" required>
        <option value="Erkek">Erkek</option>
        <option value="Kadın">Kadın</option>
    </select><br>

    <label>Yaş:</label>
    <input type="number" name="age" required><br>

    <h3>Uçuş Bilgileri</h3>
    <label>Hareket Şehri:</label>
    <input type="text" name="from_city" required><br>

    <label>Varış Şehri:</label>
    <input type="text" name="to_city" required><br>

    <label>Yolcu Sayısı:</label>
    <input type="number" name="passenger_count" required><br>

    <label>Başlangıç Zamanı:</label>
    <input type="datetime-local" name="start_time" required><br>

    <label>Bitiş Zamanı:</label>
    <input type="datetime-local" name="end_time" required><br>

    <label>Uçak ID:</label>
    <input type="number" name="plane_id" required><br>

    <label>Havaalanı ID:</label>
    <input type="number" name="airport_id" required><br>

    <button type="submit">Uçuş Ekle</button>
</form>
</body>
</html>
