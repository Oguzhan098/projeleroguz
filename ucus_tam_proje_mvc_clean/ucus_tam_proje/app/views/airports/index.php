<h1>Havaalanları</h1>
<p><a href="/airports/new">Yeni Havaalanı</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr><th>IATA</th><th>ICAO</th><th>Ad</th><th>Şehir</th><th>Ülke</th><th>Zaman Dilimi</th></tr></thead>
  <tbody>
  <?php foreach ($airports as $a): ?>
    <tr>
      <td><?= htmlspecialchars($a['iata_code']) ?></td>
      <td><?= htmlspecialchars($a['icao_code']) ?></td>
      <td><?= htmlspecialchars($a['name']) ?></td>
      <td><?= htmlspecialchars($a['city']) ?></td>
      <td><?= htmlspecialchars($a['country']) ?></td>
      <td><?= htmlspecialchars($a['timezone']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
