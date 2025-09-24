<h1>Yeni Kişi</h1>
<form method="post" action="/people">
  <label>Ad: <input name="first_name" required></label><br/>
  <label>Soyad: <input name="last_name" required></label><br/>
  <label>Email: <input name="email" type="email"></label><br/>
  <label>Telefon: <input name="phone"></label><br/>
  <label>TC/Pass No: <input name="national_id"></label><br/>
  <label>Doğum Tarihi: <input name="birth_date" type="date"></label><br/>
  <button type="submit">Kaydet</button>
</form>
