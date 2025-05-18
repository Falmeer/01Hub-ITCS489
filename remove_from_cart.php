<?php
session_start();

// Check if product ID is provided and user is logged in
if (isset($_POST['product_id']) && isset($_SESSION['cart'])) {
    $product_id = $_POST['product_id'];

    // Remove product from cart
    unset($_SESSION['cart'][$product_id]);
}

// Redirect back to the cart page
header("Location: cart.php");
exit;
?>
