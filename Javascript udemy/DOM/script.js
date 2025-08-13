// 1. Element Seçme
const baslik = document.getElementById("baslik");
const paragraflar = document.getElementsByClassName("paragraf");
const ilkParagraf = document.querySelector(".paragraf");
const butonDegistir = document.getElementById("btnDegistir");
const butonEkle = document.getElementById("btnEkle");
const butonSil = document.getElementById("btnSil");
const liste = document.getElementById("liste");
const btnEkleListe = document.getElementById("btnEkleListe");
const inputMetin = document.getElementById("inputMetin");
const formOrnek = document.getElementById("formOrnek");
const inputAd = document.getElementById("inputAd");

// 2. İçerik Değiştirme
butonDegistir.addEventListener("click", function() {
    baslik.innerText = "Başlık Değişti!";
    baslik.classList.toggle("kirmizi"); // Class ekle/kaldır
    baslik.style.fontWeight = "bold";   // Stil değişikliği
});

// 3. Yeni Eleman Ekleme
butonEkle.addEventListener("click", function() {
    const yeniP = document.createElement("p");
    yeniP.innerText = "Yeni eklenen paragraf.";
    yeniP.className = "paragraf";
    document.body.appendChild(yeniP);
});

// 4. Eleman Silme
butonSil.addEventListener("click", function() {
    if(paragraflar.length > 0) {
        paragraflar[0].remove();
    } else {
        alert("Silinecek paragraf kalmadı!");
    }
});

// 5. Listeye Eleman Ekleme
btnEkleListe.addEventListener("click", function() {
    const deger = inputMetin.value.trim();
    if(deger === "") {
        alert("Lütfen bir şey yazın!");
        return;
    }
    const li = document.createElement("li");
    li.textContent = deger;
    liste.appendChild(li);
    inputMetin.value = "";
});

// 6. Form Gönderme ve Doğrulama
formOrnek.addEventListener("submit", function(e) {
    e.preventDefault();
    if(inputAd.value.trim() === "") {
        alert("Adınızı girin!");
        return;
    }
    alert("Merhaba " + inputAd.value + ", form gönderildi!");
    inputAd.value = "";
});
