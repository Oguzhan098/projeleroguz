<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Ürünler</title>
</head>
<body>
  <h1>Ürün Listesi</h1>
  <ul>
    <?php foreach ($data['products'] as $product): ?>
      <li><?= $product['name'] ?></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
