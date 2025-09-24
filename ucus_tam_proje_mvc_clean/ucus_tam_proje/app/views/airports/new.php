<h1>Yeni Havaalanı</h1>
<form method="post" action="/airports">
  <label>IATA: <input name="iata_code" maxlength="3" required></label><br/>
  <label>ICAO: <input name="icao_code" maxlength="4"></label><br/>
  <label>Ad: <input name="name" required></label><br/>
  <label>Şehir: <input name="city"></label><br/>
  <label>Ülke: <input name="country"></label><br/>
  <label>Zaman Dilimi: <input name="timezone" placeholder="Europe/Istanbul"></label><br/>
  <button type="submit">Kaydet</button>
</form>
