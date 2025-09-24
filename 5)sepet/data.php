<?php

session_start();

// Ürün listesi
$products = [
    1 => ['name' => 'Laptop', 'price' => 15000],
    2 => ['name' => 'Telefon', 'price' => 10000],
    3 => ['name' => 'Kulaklık', 'price' => 500],
    4 => ['name' => 'Klavye', 'price' => 750],
];

// Sepet başlat
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

