<?php
$orders = $data['orders'];
$flash = $data['flash'];
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
    <a href="index.php?url=staff/dashboard">
        <img src="images/01hub-logo.png" alt="01 HUB Logo" class="logo">
    </a>
    <ul>
        <li><i class="fas fa-home"></i><a href="index.php?url=staff/dashboard">Dashboard</a></li>
        <li><i class="fas fa-box"></i><a href="index.php?url=products/manage">Manage Products</a></li>
        <li><i class="fas fa-truck"></i><a href="index.php?url=orders/manage">Manage Orders</a></li>
        <li><i class="fas fa-sign-out-alt"></i><a href="index.php?url=auth/logout">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-truck me-2"></i>Manage Orders</h2>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> flash-message">
                <?= htmlspecialchars($flash['message']) ?>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td>#<?= $o['id'] ?></td>
                            <td><?= htmlspecialchars($o['username']) ?></td>
                            <td><?= number_format($o['total_price'], 2) ?> BD</td>
                            <td>
                                <form method="POST" class="d-flex align-items-center gap-2" action="index.php?url=orders/manage">
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
