// ================= While Döngüsü =================
let i = 1;
let whileOutput = "";     //Bu değişken, döngü sırasında oluşan metinleri biriktirmek için kullanılacak.
                                  //HTML’de sonuçları göstermek için kullanılacak.

while (i <= 5) {
    console.log("While döngüsü: " + i);
    whileOutput += "While döngüsü: " + i + "<br>";            //whileOutput değişkenine yeni metin ekler.
                                                              // += kullanımı: önceki içeriğe ekleme yapar.
    i++;                                                      //i değerini 1 artırır.
}

document.getElementById("whileResult").innerHTML = whileOutput;           //HTML sayfasında id="whileResult" olan div veya element seçilir.
                                                                                     //innerHTML ile döngü sonucu HTML olarak eklenir.
                                                                                         //<br> etiketleri sayesinde metin satır satır görünür.

/*
✅ Özet Akış:

i = 1 ile başla.
while (i <= 5) koşulu true olduğu sürece:
Konsola yaz (console.log)
whileOutput değişkenine ekle (+=)
Sayacı 1 artır (i++)
Döngü bittiğinde whileOutput HTML elementine aktarılır ve sayfada görünür.
*/



// ================= Do While Döngüsü =================
let j = 1;
let doWhileOutput = "";

do {
    console.log("Do While döngüsü: " + j);
    doWhileOutput += "Do While döngüsü: " + j + "<br>";
    j++;
} while (j <= 5);

document.getElementById("doWhileResult").innerHTML = doWhileOutput;


// ================= For Döngüsü =================
let forOutput = "";

for (let k = 1; k <= 5; k++) {
    console.log("For döngüsü: " + k);
    forOutput += "For döngüsü: " + k + "<br>";
}

document.getElementById("forResult").innerHTML = forOutput;

 /*
          While Döngüsü
Şart doğru olduğu sürece çalışır.
Önce şart kontrol edilir, sonra kod çalışır.

       Do While Döngüsü
Önce kod çalışır, sonra şart kontrol edilir.

En az 1 kez çalışması garanti.

       For Döngüsü
Başlangıç değeri, koşul ve artırma/azaltma tek satırda tanımlanır.
Genelde sabit sayıda tekrar için kullanılır.

*/
