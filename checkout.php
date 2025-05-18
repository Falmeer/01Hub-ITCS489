<?php
session_start();
require 'db.php';

// Redirect to login if not logged in or cart is empty
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total_price = 0;

// Calculate total price and validate stock
foreach ($cart as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT price, quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product || $product['quantity'] < $quantity) {
        $_SESSION['checkout_error'] = "Insufficient stock for product ID: $product_id";
        header("Location: cart.php");
        exit;
    }

    $total_price += $product['price'] * $quantity;
}

// Insert into checkout table (optional tracking)
$stmt = $pdo->prepare("INSERT INTO checkout (user_id, total_price) VALUES (?, ?)");
$stmt->execute([$user_id, $total_price]);
$checkout_id = $pdo->lastInsertId();

// Insert into orders table
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmt->execute([$user_id, $total_price]);
$order_id = $pdo->lastInsertId();

// Insert order items and update inventory
foreach ($cart as $product_id => $quantity) {
    // Insert into order_items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                           VALUES (?, ?, ?, (SELECT price FROM products WHERE id = ?))");
    $stmt->execute([$order_id, $product_id, $quantity, $product_id]);

    // Reduce quantity only if sufficient
    $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
    $stmt->execute([$quantity, $product_id, $quantity]);
}

// Clear cart
unset($_SESSION['cart']);

// Success flag for thank you page
$_SESSION['order_success'] = true;

// Redirect to thank you page
header("Location: thank_you.php");
exit;
?>
