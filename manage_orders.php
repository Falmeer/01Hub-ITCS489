<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit;
}

function flash($msg, $type = 'success') {
    $_SESSION['flash'] = ['message' => $msg, 'type' => $type];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $orderId]);
    flash("Order #$orderId updated to '$status'");
    header("Location: manage_orders.php");
    exit;
}

$stmt = $pdo->query("
    SELECT o.*, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
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

        h2 {
            color: #00bcd4;
        }

        table {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .btn-primary {
            background-color: #00bcd4;
            border: none;
        }

        .btn-primary:hover {
            background-color: #009cb3;
        }

        .flash-message {
            margin-bottom: 20px;
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="staff_dashboard.php">
        <img src="images/01hub-logo.png" alt="01 HUB Logo" class="logo">
    </a>
    <ul>
        <li><i class="fas fa-home"></i><a href="staff_dashboard.php">Dashboard</a></li>
        <li><i class="fas fa-box"></i><a href="manage_products.php">Manage Products</a></li>
        <li><i class="fas fa-truck"></i><a href="manage_orders.php">Manage Orders</a></li>
        <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-truck me-2"></i>Manage Orders</h2>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?> flash-message">
                <?= $_SESSION['flash']['message'] ?>
                <?php unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td>#<?= $o['id'] ?></td>
                            <td><?= htmlspecialchars($o['username']) ?></td>
                            <td><?= number_format($o['total_price'], 2) ?> BD</td>
                            <td>
                                <form method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <select name="status" class="form-select">
                                        <?php
                                        $statuses = ['acknowledged', 'in process', 'in transit', 'completed'];
                                        foreach ($statuses as $s) {
                                            $selected = ($s === $o['status']) ? 'selected' : '';
                                            echo "<option value='$s' $selected>$s</option>";
                                        }
                                        ?>
                                    </select>
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                </form>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($o['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($orders)) echo "<tr><td colspan='6' class='text-center'>No orders found.</td></tr>"; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>