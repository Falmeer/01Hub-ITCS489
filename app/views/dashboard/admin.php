<?php
$orders = $data['orders'];
$reviews = $data['reviews'];
$staffSuccess = $data['staffSuccess'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: #fff;
            border-right: 1px solid #ddd;
            position: fixed;
            padding: 20px;
        }
        .sidebar .logo {
            width: 120px;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
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
        .section {
            margin-bottom: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #e0f7fa;
        }
        .form-input {
            margin-top: 15px;
        }
        input[type=text], input[type=password] {
            padding: 8px;
            width: 250px;
            margin-right: 10px;
        }
        input[type=submit] {
            padding: 8px 16px;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 4px;
        }
        .success {
            margin-top: 10px;
            color: green;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="images/01hub-logo.png" alt="Logo" class="logo">
    <ul>
        <li><a href="index.php?url=admin/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="index.php?url=auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="section">
        <h2>Create Supplier Account</h2>
        <form method="POST" action="index.php?url=admin/dashboard">
            <div class="form-input">
                <input type="text" name="staff_username" placeholder="Supplier Username" required>
                <input type="password" name="staff_password" placeholder="Password" required>
                <input type="submit" name="new_staff" value="Create">
            </div>
        </form>
        <?php if (!empty($staffSuccess)): ?>
            <p class="success"><?= htmlspecialchars($staffSuccess) ?></p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>All Orders</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= $order['total_price'] ?> BD</td>
                    <td><?= ucfirst($order['status']) ?></td>
                    <td><?= $order['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="section">
        <h2>Product Reviews</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?= htmlspecialchars($review['product_name']) ?></td>
                    <td><?= htmlspecialchars($review['username']) ?></td>
                    <td>⭐ <?= $review['rating'] ?></td>
                    <td><?= htmlspecialchars($review['comment']) ?></td>
                    <td><?= $review['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
