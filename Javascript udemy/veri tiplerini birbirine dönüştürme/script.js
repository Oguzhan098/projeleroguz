function donustur() {
    let cikti = "";

    // String → Number
    let sayiStr = "123";
    let sayi = Number(sayiStr);
    cikti += `"${sayiStr}" typeof: ${typeof sayiStr} → ${sayi} typeof: ${typeof sayi} <br>`;

    // Number → String
    let num = 456;
    let str = String(num);
    cikti += `${num} typeof: ${typeof num} → "${str}" typeof: ${typeof str} <br>`;

    // Boolean → String
    let bool = true;
    let boolStr = String(bool);
    cikti += `${bool} typeof: ${typeof bool} → "${boolStr}" typeof: ${typeof boolStr} <br>`;

    // String → Boolean
    let text = "hello";
    let textBool = Boolean(text); // boş string olsaydı false
    cikti += `"${text}" → ${textBool} typeof: ${typeof textBool} <br>`;

    // null → Number
    let n = null;
    let nNum = Number(n);
    cikti += `null → ${nNum} typeof: ${typeof nNum} <br>`;

    // undefined → Number
    let u;
    let uNum = Number(u);
    cikti += `undefined → ${uNum} typeof: ${typeof uNum} <br>`;

    // Otomatik (type coercion)
    let sonuc = "5" + 10; // string + number = string
    cikti += `"5" + 10 → ${sonuc} typeof: ${typeof sonuc} <br>`;

    let sonuc2 = "5" * 2; // string * number = number
    cikti += `"5" * 2 → ${sonuc2} typeof: ${typeof sonuc2} <br>`;

    document.getElementById("sonuc").innerHTML = cikti;
}
