<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order History - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f5f7fa;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #fff;
            border-right: 1px solid #ddd;
            position: fixed;
            padding: 20px;
        }

        .sidebar img.logo {
            width: 120px;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
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
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .order {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .order h3 {
            color: #00bcd4;
        }

        .order p {
            margin: 5px 0;
        }

        .order ul {
            margin-top: 10px;
            list-style: disc inside;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a href="customer_dashboard.php"><img src="images/01hub-logo.png" class="logo" alt="01 HUB"></a>
        <ul>
            <li><i class="fas fa-home"></i><a href="customer_dashboard.php">Home</a></li>
            <li><i class="fas fa-box"></i><a href="products.php">Products</a></li>
            <li><i class="fas fa-shopping-cart"></i><a href="cart.php">Cart</a></li>
            <li><i class="fas fa-box-open"></i><a href="order_history.php">Order History</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <h1>Your Order History</h1>
        <?php if ($orders): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <h3>Order #<?= $order['id'] ?> (<?= $order['status'] ?>)</h3>
                    <p><strong>Total:</strong> <?= $order['total_price'] ?> BD</p>
                    <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
                    <ul>
                        <?php
                        $stmt = $pdo->prepare("SELECT p.name, oi.quantity, oi.price FROM order_items oi
                                           JOIN products p ON p.id = oi.product_id
                                           WHERE oi.order_id = ?");
                        $stmt->execute([$order['id']]);
                        $items = $stmt->fetchAll();
                        foreach ($items as $item):
                            ?>
                            <li><?= $item['name'] ?> × <?= $item['quantity'] ?> = <?= $item['quantity'] * $item['price'] ?> BD</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No orders yet.</p>
        <?php endif; ?>
    </div>

</body>

</html>