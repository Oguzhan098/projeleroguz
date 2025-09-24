<h1>Kişiler</h1>
<p><a href="/people/new">Yeni Kişi</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr><th>Ad</th><th>Soyad</th><th>Email</th><th>Telefon</th></tr></thead>
  <tbody>
  <?php foreach ($people as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['first_name']) ?></td>
      <td><?= htmlspecialchars($p['last_name']) ?></td>
      <td><?= htmlspecialchars($p['email']) ?></td>
      <td><?= htmlspecialchars($p['phone']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
