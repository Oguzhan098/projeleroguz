function calistir() {
    let cikti = "";

    // var: function-scope
    var x = 10;
    if (true) {
        var x = 20; // aynı değişken, güncellenir
    }
    cikti += `var ile tanımlanan x: ${x} <br>`;

    // let: block-scope
    let y = 10;
    if (true) {
        let y = 20; // farklı block içindeki y, ayrı değişkendir
    }
    cikti += `let ile tanımlanan y: ${y} <br>`;

    // const: block-scope, sabit (değiştirilemez)
    const z = 10;
    // z = 20; // ❌ Hata: const değiştirilemez
    cikti += `const ile tanımlanan z: ${z} <br>`;

    document.getElementById("sonuc").innerHTML = cikti;
}
