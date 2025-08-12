// ================= While Döngüsü =================
let i = 1;
let whileOutput = "";

while (i <= 5) {
    console.log("While döngüsü: " + i);
    whileOutput += "While döngüsü: " + i + "<br>";
    i++;
}

document.getElementById("whileResult").innerHTML = whileOutput;


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
Genelde sabit sayıda tekrar için kullanılır.  */
