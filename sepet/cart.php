<?php
require 'functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear'])) {
        clearCart();
    } else {
        foreach ($_SESSION['cart'] as $id => $item) {
            if (isset($_POST['remove_' . $id])) {
                removeFromCart($id);
            }
        }
    }
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sepet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Sepetiniz</h1>
    <a href="index.php">Ürünlere Dön</a>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Sepetiniz boş.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <li>
                    <?= $item['name'] ?> - <?= $item['price'] ?> TL × <?= $item['quantity'] ?>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="remove_<?= $item['id'] ?>">Kaldır</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="post">
            <button type="submit" name="clear">Tümünü Temizle</button>
        </form>
    <?php endif; ?>
</body>
</html>
