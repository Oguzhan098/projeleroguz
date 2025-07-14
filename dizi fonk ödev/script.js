document.getElementById("cizBtn").addEventListener("click", function() {
  const satirSayisiInput = document.getElementById("satirSayisi");
  const satirSayisi = parseInt(satirSayisiInput.value);
  const cikti = document.getElementById("cikti");

  if (isNaN(satirSayisi) || satirSayisi <= 0) {
    cikti.textContent = "Lütfen pozitif bir sayı giriniz.";
    return;
  }

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
});
