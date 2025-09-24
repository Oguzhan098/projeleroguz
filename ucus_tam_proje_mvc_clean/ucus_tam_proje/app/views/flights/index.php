<h1>Uçuşlar</h1>
<p><a href="/flights/new">Yeni Uçuş</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr><th>No</th><th>Uçak</th><th>Kalkış</th><th>Varış</th><th>Durum</th><th>Kapı</th><th>Detay</th></tr></thead>
  <tbody>
  <?php foreach ($flights as $f): ?>
    <tr>
      <td><?= htmlspecialchars($f['flight_no']) ?></td>
      <td><?= htmlspecialchars($f['registration']) ?> (<?= htmlspecialchars($f['model']) ?>)</td>
      <td><?= htmlspecialchars($f['origin_name']) ?> - <?= htmlspecialchars($f['departure_time_utc']) ?></td>
      <td><?= htmlspecialchars($f['destination_name']) ?> - <?= htmlspecialchars($f['arrival_time_utc']) ?></td>
      <td><?= htmlspecialchars($f['status']) ?></td>
      <td><?= htmlspecialchars($f['gate']) ?></td>
      <td><a href="/flights/<?= (int)$f['id'] ?>">Yolcular</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
