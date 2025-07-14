<head>
    <title>Ürün Listesi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Ürünler</h1>
<a href="cart.php">Sepeti Görüntüle</a>
<div class="product-list">
    <?php
    require 'functions.php';

    $products = [
        ['id' => 1, 'name' => 'Kamp Çadırı', 'price' => 10000],
        ['id' => 2, 'name' => 'Sırt Çantası', 'price' => 2500],
        ['id' => 3, 'name' => 'Su Geçirmez Ayakkabı', 'price' => 5000]
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($products as $product) {
            if (isset($_POST['add_' . $product['id']])) {
                addToCart($product);
            }
        }
        header("Location: index.php");
        exit;
    }
    ?>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h3><?= $product['name'] ?></h3>
            <p>Fiyat: <?= $product['price'] ?> TL</p>
            <form method="post">
                <button type="submit" name="add_<?= $product['id'] ?>">Sepete Ekle</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
