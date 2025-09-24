<?php global $products;
require 'data.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepet</title>
    <style>
        body {
            font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        table { border-collapse: collapse; width: 60%; margin-bottom: 30px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        a.button { padding: 6px 12px; border-radius: 4px; text-decoration: none; color: white; }
        .add { background-color: #27ae60; }
        .remove { background-color: #c0392b; }
        .clear { background-color: #e67e22; }
    </style>
</head>
<body>

<h2>Ürünler</h2>
<table>
    <tr>
        <th>Ürün</th>
        <th>Fiyat</th>
        <th>İşlem</th>
    </tr>
    <?php foreach ($products as $id => $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= number_format($product['price'], 2) ?> ₺</td>
            <td><a class="button add" href="process.php?add=<?= $id ?>">Sepete Ekle</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Sepet</h2>
<?php if (empty($_SESSION['cart'])): ?>
    <p>Sepetiniz boş.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Ürün</th>
            <th>Fiyat</th>
            <th>Adet</th>
            <th>Toplam</th>
            <th>İşlem</th>
        </tr>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item):
            $subtotal = $item['product']['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($item['product']['name']) ?></td>
                <td><?= number_format($item['product']['price'], 2) ?> ₺</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($subtotal, 2) ?> ₺</td>
                <td><a class="button remove" href="process.php?remove=<?= $id ?>">Sil</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="3">Genel Toplam</th>
            <th colspan="2"><?= number_format($total, 2) ?> ₺</th>
        </tr>
    </table>
    <a class="button clear" href="process.php?clear=true">Sepeti Boşalt</a>
<?php endif; ?>

</body>
</html>
