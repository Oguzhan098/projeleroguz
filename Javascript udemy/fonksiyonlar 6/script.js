// 1️⃣ Normal Fonksiyon (Function Declaration)
function selamVer() {
    console.log("Merhaba! Bu mesaj console'da görünüyor.");
    alert("Merhaba! Bu mesaj tarayıcıda görünüyor.");
}

// 2️⃣ Fonksiyon İfadesi (Function Expression)
const topla = function(a, b) {
    return a + b;
};

// 3️⃣ Ok Fonksiyonu (Arrow Function)
const carp = (a, b) => a * b;

// Sayfa yüklendiğinde örnekleri çalıştır
console.log("Toplama Örneği (5 + 10):", topla(5, 10));
console.log("Çarpma Örneği (3 × 4):", carp(3, 4));
