<?php
$user = $data['user'];
$total_orders = $data['total_orders'];
$total_spent = $data['total_spent'];
$cart_count = $data['cart_count'];
$topProducts = $data['topProducts'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
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
            padding: 40px;
        }

        .welcome {
            font-size: 26px;
            margin-bottom: 30px;
            color: #333;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1;
            min-width: 250px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #00bcd4;
        }

        .card p {
            font-size: 18px;
            color: #333;
        }

        .quick-links {
            margin-top: 40px;
        }

        .quick-links h3 {
            margin-bottom: 15px;
            color: #555;
        }

        .quick-links a {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
            padding: 10px 20px;
            background-color: #00bcd4;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
        }

        .quick-links a:hover {
            background-color: #009cb3;
        }

        .trending-products {
            margin-top: 50px;
        }

        .trending-products h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-card {
            width: 230px;
            background: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .product-card img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            border-radius: 10px;
        }

        .product-card h4 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #333;
        }

        .product-card p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .product-card .button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #00bcd4;
            color: white;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
        }

        footer {
            margin-top: 60px;
            text-align: center;
            padding: 15px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a href="index.php?url=pages/about">
            <img src="images/01hub-logo.png" class="logo" alt="01 HUB">
        </a>
        <ul>
            <li><i class="fas fa-home"></i><a href="index.php?url=customer/dashboard">Home</a></li>
            <li><i class="fas fa-box"></i><a href="index.php?url=product/browse">Products</a></li>
            <li><i class="fas fa-shopping-cart"></i><a href="index.php?url=cart/index">Cart</a></li>
            <li><i class="fas fa-box-open"></i><a href="index.php?url=order/history">Order History</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="index.php?url=auth/logout">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="welcome">👋 Welcome to 01 Hub, <?= htmlspecialchars($user['username']) ?>!</div>

        <div class="dashboard-cards">
            <div class="card">
                <h3><i class="fas fa-clipboard-list"></i> Total Orders</h3>
                <p><?= $total_orders ?></p>
            </div>
            <div class="card">
                <h3><i class="fas fa-wallet"></i> Total Spent</h3>
                <p><?= number_format($total_spent, 2) ?> BD</p>
            </div>
            <div class="card">
                <h3><i class="fas fa-shopping-basket"></i> Items in Cart</h3>
                <p><?= $cart_count ?></p>
            </div>
        </div>

        <div class="quick-links">
            <h3>Quick Links</h3>
            <a href="index.php?url=product/browse"><i class="fas fa-store"></i> Browse Products</a>
            <a href="index.php?url=cart/index"><i class="fas fa-cart-arrow-down"></i> View Cart</a>
            <a href="index.php?url=order/history"><i class="fas fa-box-open"></i> Order History</a>
        </div>

        <div class="trending-products">
            <h3> Top Trending Products</h3>
            <div class="product-grid">
                <?php foreach ($topProducts as $product): ?>
                    <div class="product-card">
                        <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p><?= $product['price'] ?> BD</p>
                        <p>⭐ <?= number_format($product['avg_rating'], 1) ?> / 5</p>
                        <a href="index.php?url=product/detail&id=<?= $product['id'] ?>" class="button">View</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 01 HUB. Where Technology Meets Performance.</p>
    </footer>

</body>

</html>
