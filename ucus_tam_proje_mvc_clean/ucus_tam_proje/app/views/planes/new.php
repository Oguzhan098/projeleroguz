<h1>Yeni Uçak</h1>
<form method="post" action="/planes">
  <label>Kuyruk No: <input name="registration" required></label><br/>
  <label>Model: <input name="model" required></label><br/>
  <label>Üretici: <input name="manufacturer"></label><br/>
  <label>Kapasite: <input name="capacity" type="number" min="1" required></label><br/>
  <button type="submit">Kaydet</button>
</form>
