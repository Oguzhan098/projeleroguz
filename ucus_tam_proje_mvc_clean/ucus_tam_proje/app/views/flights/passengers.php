<h1>Uçuş Yolcuları</h1>
<?php if (!$flight): ?>
  <p>Uçuş bulunamadı.</p>
<?php else: ?>
  <p><strong>Uçuş No:</strong> <?= htmlspecialchars($flight['flight_no']) ?></p>
  <table border="1" cellpadding="6" cellspacing="0">
    <thead><tr><th>Ad</th><th>Soyad</th><th>Email</th><th>Koltuk</th><th>Biniş</th></tr></thead>
    <tbody>
      <?php foreach ($passengers as $ps): ?>
      <tr>
        <td><?= htmlspecialchars($ps['first_name']) ?></td>
        <td><?= htmlspecialchars($ps['last_name']) ?></td>
        <td><?= htmlspecialchars($ps['email']) ?></td>
        <td><?= htmlspecialchars($ps['seat_no']) ?></td>
        <td><?= !empty($ps['boarded']) ? 'Evet' : 'Hayır' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <h2>Yolcu Ekle</h2>
  <form method="post" action="/flights/<?= (int)$flight['id'] ?>/passengers">
    <label>Kişi ID: <input name="person_id" type="number" required></label><br/>
    <label>Koltuk: <input name="seat_no"></label><br/>
    <label>Bilet No: <input name="ticket_no"></label><br/>
    <button type="submit">Ekle</button>
  </form>
<?php endif; ?>
