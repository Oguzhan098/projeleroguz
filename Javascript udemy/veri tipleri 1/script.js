function veriTipleriniGoster() {
    // Number
    let yas = 28;

    // String
    let isim = "Oğuzhan";

    // Boolean
    let aktifMi = true;

    // Null
    let bosDeger = null;

    // Undefined
    let tanimsizDeger;

    // Sonuçları HTML'e yaz
    let cikti = `
    <strong>Number:</strong> ${yas} (${typeof yas})<br>
    <strong>String:</strong> ${isim} (${typeof isim})<br>
    <strong>Boolean:</strong> ${aktifMi} (${typeof aktifMi})<br>
    <strong>Null:</strong> ${bosDeger} (${typeof bosDeger}) ⚠️<br>
    <strong>Undefined:</strong> ${tanimsizDeger} (${typeof tanimsizDeger})<br>
  `;

    document.getElementById("sonuc").innerHTML = cikti;
}
