/*

// Switch Case ile Gün Bulma
function showDay() {
    let dayNumber = Number(document.getElementById("day").value);
    let dayName;

    switch (dayNumber) {
        case 1:
            dayName = "Pazartesi";
            break;
        case 2:
            dayName = "Salı";
            break;
        case 3:
            dayName = "Çarşamba";
            break;
        case 4:
            dayName = "Perşembe";
            break;
        case 5:
            dayName = "Cuma";
            break;
        case 6:
            dayName = "Cumartesi";
            break;
        case 7:
            dayName = "Pazar";
            break;
        default:
            dayName = "Hatalı gün numarası!";
    }

    // Hem ekrana yazdırıyoruz hem de konsola logluyoruz
    document.getElementById("result").textContent = dayName;
    console.log("Seçilen gün: " + dayName);
}

*/
// 📌 Switch Case yapısı, bir değişkenin değerine göre farklı kod bloklarını çalıştırır

let gun = new Date().getDay(); // Bugünün gün numarasını alır (0: Pazar, 1: Pazartesi ... 6: Cumartesi)

console.log("Bugün gün numarası:", gun);

switch (gun) {
    case 0:
        console.log("Bugün Pazar");
        break;
    case 1:
        console.log("Bugün Pazartesi");
        break;
    case 2:
        console.log("Bugün Salı");
        break;
    case 3:
        console.log("Bugün Çarşamba");
        break;
    case 4:
        console.log("Bugün Perşembe");
        break;
    case 5:
        console.log("Bugün Cuma");
        break;
    case 6:
        console.log("Bugün Cumartesi");
        break;
    default:
        console.log("Geçersiz gün!");
}

// 🔹 NOT: "break" komutu kullanılmazsa, bir sonraki case de çalışmaya devam eder.


//1️⃣ Kullanım Amacı
// if-else → Daha esnek, hem mantıksal ifadeler (>, <, ===, &&, ||) hem de değer karşılaştırmaları için kullanılır.
//
// switch-case → Genellikle tek bir değişkenin belirli değerlere eşitliğini kontrol etmek için kullanılır.