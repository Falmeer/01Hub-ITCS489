<?php $orders = $data['orders']; ?>

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
    <a href="index.php?url=customer/dashboard">
        <img src="images/01hub-logo.png" class="logo" alt="01 HUB">
    </a>
    <ul>
        <li><i class="fas fa-home"></i><a href="index.php?url=customer/dashboard"> Home</a></li>
        <li><i class="fas fa-box"></i><a href="index.php?url=product/browse"> Products</a></li>
        <li><i class="fas fa-shopping-cart"></i><a href="index.php?url=cart/index"> Cart</a></li>
        <li><i class="fas fa-box-open"></i><a href="index.php?url=order/history"> Order History</a></li>
        <li><i class="fas fa-sign-out-alt"></i><a href="index.php?url=auth/logout"> Logout</a></li>
    </ul>
</div>

<div class="main">
    <h1>Your Order History</h1>
    <?php if ($orders): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h3>Order #<?= $order['id'] ?> (<?= ucfirst($order['status']) ?>)</h3>
                <p><strong>Total:</strong> <?= number_format($order['total_price'], 2) ?> BD</p>
                <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?> = <?= number_format($item['quantity'] * $item['price'], 2) ?> BD</li>
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
