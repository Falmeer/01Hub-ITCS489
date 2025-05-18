<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supplier Dashboard - 01 HUB</title>
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
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
    <a href="staff_dashboard.php">
        <img src="images/01hub-logo.png" class="logo" alt="01 HUB">
    </a>
    <ul>
        <li><i class="fas fa-home"></i><a href="staff_dashboard.php">Dashboard</a></li>
        <li><i class="fas fa-box"></i><a href="manage_products.php">Manage Products</a></li>
        <li><i class="fas fa-truck"></i><a href="manage_orders.php">Manage Orders</a></li>
        <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="welcome">🧑‍💼 Welcome back, <?= htmlspecialchars($user['username']) ?>!</div>

    <div class="dashboard-cards">
        <div class="card">
            <h3><i class="fas fa-boxes"></i> Manage Products</h3>
            <p>Add, update, or remove products from the shop.</p>
        </div>
        <div class="card">
            <h3><i class="fas fa-truck"></i> Manage Orders</h3>
            <p>Update status, track, and fulfill orders efficiently.</p>
        </div>
        <div class="card">
            <h3><i class="fas fa-user-check"></i> Supplier Access</h3>
            <p>Only authorized suppliers can manage this panel.</p>
        </div>
    </div>

    <div class="quick-links">
        <h3>Quick Actions</h3>
        <a href="manage_products.php"><i class="fas fa-plus-circle"></i> Products</a>
        <a href="manage_orders.php"><i class="fas fa-clipboard-check"></i> Orders</a>
    </div>
</div>

<footer>
    <p>© 2025 01 HUB. Where Technology Meets Performance.</p>
</footer>

</body>
</html>
