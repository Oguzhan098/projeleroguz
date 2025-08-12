// Sayfadaki output div'ine yazdırmak için
const output = document.getElementById("output");

function yazdir(metin) {
    console.log(metin); // Konsola yazdır
    output.innerHTML += metin + "<br>"; // Sayfaya yazdır
}

// 1️⃣ Karşılaştırma Operatörleri
yazdir("=== Karşılaştırma Operatörleri ===");

let a = 5;
let b = "5";

yazdir("a == b: " + (a == b));   // true (Tip dönüşümü yapar)
yazdir("a === b: " + (a === b)); // false (Tip dönüşümü yapmaz)
yazdir("a != b: " + (a != b));   // false
yazdir("a !== b: " + (a !== b)); // true
yazdir("a > 3: " + (a > 3));     // true
yazdir("a < 10: " + (a < 10));   // true
yazdir("a >= 5: " + (a >= 5));   // true
yazdir("a <= 4: " + (a <= 4));   // false

yazdir("--------------------------------");

// 2️⃣ Koşullar (if-else)
yazdir("=== Koşullar (if-else) ===");

let yas = 18;

if (yas > 18) {
    yazdir("Reşitsin ve yetişkinsin");
} else if (yas === 18) {
    yazdir("Tam 18 yaşındasın, reşitsin");
} else {
    yazdir("Henüz reşit değilsin");
}

yazdir("--------------------------------");

// 3️⃣ Ternary Operatör
yazdir("=== Ternary Operatör ===");

let puan = 75;
let sonuc = (puan >= 50) ? "Geçti" : "Kaldı";
yazdir(`Puan: ${puan} → Sonuç: ${sonuc}`);

puan = 40;
sonuc = (puan >= 50) ? "Geçti" : "Kaldı";
yazdir(`Puan: ${puan} → Sonuç: ${sonuc}`);

//CONSOLE YAZDIRDIGIMIZ ORNEKLER

// 1️⃣ Basit mesaj yazdırma
console.log("Merhaba Console!");

// 2️⃣ Değişken değeri yazdırma
let isim = "Oğuzhan";
console.log("İsim:", isim);

// 3️⃣ Hata mesajı
console.error("Bu bir hata mesajıdır!");

// 4️⃣ Uyarı mesajı
console.warn("Bu bir uyarıdır!");

// 5️⃣ Tablo şeklinde yazdırma
let ogrenciler = [
    { ad: "Ali", yas: 21 },
    { ad: "Ayşe", yas: 22 },
    { ad: "Mehmet", yas: 23 }
];
console.table(ogrenciler);
