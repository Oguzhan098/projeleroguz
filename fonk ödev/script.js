function ucgen(satirSayisi) {
  const cikti = document.getElementById("cikti");
  let sonuc = "";
  let satir = 1;

  while (satir <= satirSayisi) {
    for (let i = 1; i <= satir; i++) {
      sonuc += "O ";
    }
    sonuc += "\n";
    satir++;
  }

  cikti.textContent = sonuc;
}
