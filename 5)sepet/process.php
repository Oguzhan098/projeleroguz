<?php

require 'data.php';

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if (isset($products[$id])) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = ['product' => $products[$id], 'quantity' => 1];
        } else {
            $_SESSION['cart'][$id]['quantity']++;
        }
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: index.php");
    exit;
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: index.php");
    exit;
}

