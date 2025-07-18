<?php
session_start();

// Sabit ürün listesi
$urunler = [
    1 => ['ad' => 'Kamp Çadırı', 'fiyat' => 1000],
    2 => ['ad' => 'Termos', 'fiyat' => 350],
    3 => ['ad' => 'Uyku Tulumu', 'fiyat' => 750]
];

// Sepeti başlat
if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

// Ürün sepete ekle
if (isset($_GET['ekle'])) {
    $id = (int)$_GET['ekle'];
    if (isset($urunler[$id])) {
        if (isset($_SESSION['sepet'][$id])) {
            $_SESSION['sepet'][$id]['adet']++;
        } else {
            $_SESSION['sepet'][$id] = [
                'ad' => $urunler[$id]['ad'],
                'fiyat' => $urunler[$id]['fiyat'],
                'adet' => 1
            ];
        }
    }
    header("Location: index.php");
    exit;
}

// Ürün sepetten çıkar
if (isset($_GET['cikar'])) {
    $id = (int)$_GET['cikar'];
    unset($_SESSION['sepet'][$id]);
    header("Location: index.php");
    exit;
}

// Tümünü sepetten sil
if (isset($_GET['temizle'])) {
    $_SESSION['sepet'] = [];
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepet Uygulaması</title>
    <style>
        body { font-family: Arial; background-color: #f0f0f0; padding: 20px; }
        .urun, .sepet { background: #fff; padding: 15px; margin-bottom: 10px; border: 1px solid #ccc; }
        .urun a, .sepet a { margin-left: 10px; }
    </style>
</head>
<body>

<h2>Ürünler</h2>
<?php foreach ($urunler as $id => $urun): ?>
    <div class="urun">
        <strong><?= $urun['ad'] ?></strong> - <?= $urun['fiyat'] ?> TL
        <a href="?ekle=<?= $id ?>">[Sepete Ekle]</a>
    </div>
<?php endforeach; ?>

<hr>

<h2>Sepet</h2>
<?php if (!empty($_SESSION['sepet'])): ?>
    <?php $toplam = 0; ?>
    <?php foreach ($_SESSION['sepet'] as $id => $urun): ?>
        <div class="sepet">
            <?= $urun['ad'] ?> - <?= $urun['adet'] ?> adet - <?= $urun['fiyat'] * $urun['adet'] ?> TL
            <a href="?cikar=<?= $id ?>">[Kaldır]</a>
        </div>
        <?php $toplam += $urun['fiyat'] * $urun['adet']; ?>
    <?php endforeach; ?>
    <strong>Toplam: <?= $toplam ?> TL</strong>
    <br><br>
    <a href="?temizle=true">[Sepeti Temizle]</a>
<?php else: ?>
    <p>Sepetiniz boş.</p>
<?php endif; ?>

