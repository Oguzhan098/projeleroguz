<?php

session_start();

function addToCart($product)
{
    $id = $product['id'];
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = $product;
        $_SESSION['cart'][$id]['quantity'] = 1;
    } else {
        $_SESSION['cart'][$id]['quantity']++;
    }
}

function removeFromCart($id)
{
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

function clearCart()
{
    $_SESSION['cart'] = [];
}
