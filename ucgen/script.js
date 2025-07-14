function ucgenDuz(satir) {
  let sonuc = "";
  for (let i = 1; i <= satir; i++) {
    sonuc += "*".repeat(i) + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}

function ucgenTers(satir) {
  let sonuc = "";
  for (let i = satir; i >= 1; i--) {
    sonuc += "*".repeat(i) + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}

function ucgenSag(satir) {
  let sonuc = "";
  for (let i = 1; i <= satir; i++) {
    sonuc += " ".repeat(satir - i) + "*".repeat(i) + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}

function ucgenSol(satir) {
  let sonuc = "";
  for (let i = 1; i <= satir; i++) {
    let yildizlar = "*".repeat(i);
    sonuc += yildizlar + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}


function ucgenEskenar(satir) {
  let sonuc = "";
  for (let i = 1; i <= satir; i++) {
    let bosluk = " ".repeat(satir - i);
    let yildizlar = "*".repeat(2 * i - 1);
    sonuc += bosluk + yildizlar + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}

function ucgenTersEskenar(satir) {
  let sonuc = "";
  for (let i = satir; i >= 1; i--) {
    let bosluk = " ".repeat(satir - i);
    let yildizlar = "*".repeat(2 * i - 1);
    sonuc += bosluk + yildizlar + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}

function ucgenTersSag(satir) {
  let sonuc = "";
  for (let i = satir; i >= 1; i--) {
    let bosluk = " ".repeat(satir - i);
    let yildizlar = "*".repeat(i);
    sonuc += bosluk + yildizlar + "\n";
  }
  document.getElementById("cikti").textContent = sonuc;
}
