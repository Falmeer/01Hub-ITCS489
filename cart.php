<?php
session_start();
require 'db.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cart - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: #fff;
            border-right: 1px solid #ddd;
            position: fixed;
            padding: 20px;
        }

        .sidebar img.logo {
            width: 120px;
            margin-bottom: 30px;
            cursor: pointer;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .sidebar ul li i {
            margin-right: 10px;
            color: #00bcd4;
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
        }

        .main {
            margin-left: 260px;
            padding: 40px;
        }

        .cart-container {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-container h1 {
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item form {
            margin: 0;
        }

        .cart-item button {
            padding: 6px 12px;
            background-color: #e74c3c;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .cart-item button:hover {
            background-color: #c0392b;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #2980b9;
        }

        .checkout-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
        }

        .checkout-btn a {
            background-color: #00bcd4;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .checkout-btn a:hover {
            background-color: #009cb3;
        }

        footer {
            margin-top: 60px;
            text-align: center;
            padding: 15px;
            color: #888;
        }

        .checkout-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .action-btn {
            background-color: #00bcd4;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #009cb3;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a href="customer_dashboard.php">
            <img src="images/01hub-logo.png" alt="01 HUB Logo" class="logo">
        </a>
        <ul>
            <li><i class="fas fa-home"></i> <a href="customer_dashboard.php">Home</a></li>
            <li><i class="fas fa-box"></i> <a href="products.php">Products</a></li>
            <li><i class="fas fa-shopping-cart"></i> <a href="cart.php">Cart</a></li>
            <li><i class="fas fa-sign-out-alt"></i> <a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="cart-container">
            <h1>Your Cart</h1>

            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $product_id => $quantity): ?>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                    $stmt->execute([$product_id]);
                    $product = $stmt->fetch();
                    $subtotal = $product['price'] * $quantity;
                    $total_price += $subtotal;
                    ?>
                    <div class="cart-item">
                        <div style="display: flex; align-items: center;">
                            <img src="images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>"
                                style="width: 80px; height: 80px; object-fit: contain; border-radius: 8px; margin-right: 15px;">
                            <div>
                                <strong><?= $product['name'] ?></strong><br>
                                <?= $quantity ?> × <?= $product['price'] ?> BD = <?= $subtotal ?> BD
                            </div>
                        </div>
                        <form method="post" action="remove_from_cart.php">
                            <input type="hidden" name="product_id" value="<?= $product_id ?>">
                            <button type="submit" name="remove"><i class="fas fa-trash"></i> Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <p class="total">Total: <?= $total_price ?> BD</p>

                <div class="checkout-actions">
                    <a href="products.php" class="action-btn"><i class="fas fa-store"></i> Continue Shopping</a>
                    <a href="checkout.php" class="action-btn"><i class="fas fa-credit-card"></i> Proceed to Checkout</a>
                </div>
            <?php else: ?>
                <p style="text-align:center; color:#888;">Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>© 2025 01 HUB. Where Technology Meets Performance.</p>
    </footer>

</body>

</html>