<h1>Yeni Uçuş</h1>
<form method="post" action="/flights">
  <label>Uçuş No: <input name="flight_no" required></label><br/>
  <label>Uçak:
    <select name="plane_id" required>
      <option value="">Seçin</option>
      <?php foreach ($planes as $p): ?>
        <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['registration']) ?> (<?= htmlspecialchars($p['model']) ?>)</option>
      <?php endforeach; ?>
    </select>
  </label><br/>
  <label>Kalkış Havalimanı:
    <select name="origin_airport_id" required>
      <option value="">Seçin</option>
      <?php foreach ($airports as $a): ?>
        <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label><br/>
  <label>Varış Havalimanı:
    <select name="destination_airport_id" required>
      <option value="">Seçin</option>
      <?php foreach ($airports as $a): ?>
        <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label><br/>
  <label>Kalkış (UTC): <input name="departure_time_utc" type="datetime-local" required></label><br/>
  <label>Varış (UTC): <input name="arrival_time_utc" type="datetime-local" required></label><br/>
  <label>Durum:
    <select name="status">
      <option>scheduled</option><option>boarding</option><option>departed</option>
      <option>landed</option><option>cancelled</option><option>delayed</option>
    </select>
  </label><br/>
  <label>Kapı: <input name="gate"></label><br/>
  <button type="submit">Kaydet</button>
</form>
