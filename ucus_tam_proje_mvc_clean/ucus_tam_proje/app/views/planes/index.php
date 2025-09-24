<h1>Uçaklar</h1>
<p><a href="/planes/new">Yeni Uçak</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr><th>Kuyruk No</th><th>Model</th><th>Üretici</th><th>Kapasite</th></tr></thead>
  <tbody>
  <?php foreach ($planes as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['registration']) ?></td>
      <td><?= htmlspecialchars($p['model']) ?></td>
      <td><?= htmlspecialchars($p['manufacturer']) ?></td>
      <td><?= (int)$p['capacity'] ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
