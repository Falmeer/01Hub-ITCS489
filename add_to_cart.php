<?php
session_start();

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id || !is_numeric($product_id)) {
    header("Location: products.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0;
}

$_SESSION['cart'][$product_id] += $quantity;

header("Location: cart.php");
exit;
