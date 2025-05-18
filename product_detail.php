<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: products.php");
    exit;
}

// Fetch product info
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

// Fetch reviews
$stmt = $pdo->prepare("SELECT r.*, u.username FROM product_reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();

// Average rating
$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM product_reviews WHERE product_id = ?");
$stmt->execute([$id]);
$avg_rating = round($stmt->fetchColumn(), 1);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['rating'], $_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $rating = max(1, min(5, intval($_POST['rating'])));
    $comment = trim($_POST['comment']);

    $stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $user_id, $rating, $comment]);

    header("Location: product_detail.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= $product['name'] ?> - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Your original styling... */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
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

        .product-detail-card {
            background-color: #fff;
            border-radius: 20px;
            padding: 30px;
            max-width: 900px;
            margin: auto;
            display: flex;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product-detail-card img {
            max-width: 300px;
            border-radius: 10px;
            object-fit: contain;
        }

        .product-info {
            margin-left: 40px;
            flex: 1;
        }

        .product-info h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-info p {
            margin-bottom: 10px;
            color: #555;
        }

        .product-info .price {
            font-size: 22px;
            color: #00bcd4;
            font-weight: bold;
        }

        .product-info form {
            margin-top: 20px;
        }

        input[type=number],
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 60px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #00bcd4;
            color: #fff;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #009cb3;
        }

        .reviews,
        .add-review {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
        }

        .reviews h3,
        .add-review h3 {
            margin-bottom: 15px;
            color: #00bcd4;
        }

        .review-item {
            border-top: 1px solid #eee;
            padding: 15px 0;
        }

        .review-item:first-child {
            border-top: none;
        }

        .review-item strong {
            color: #333;
        }

        .review-item .stars {
            color: #f39c12;
        }

        textarea {
            width: 100%;
            margin-top: 10px;
            min-height: 80px;
        }

        .stars-select {
            margin-top: 10px;
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
        <div class="product-detail-card">
            <img src="images/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            <div class="product-info">
                <h1><?= $product['name'] ?></h1>
                <p><?= $product['description'] ?></p>
                <p class="price"><?= $product['price'] ?> BD</p>
                <p>Available: <?= $product['quantity'] ?></p>
                <p>Average Rating: ⭐ <?= $avg_rating ?> / 5</p>

                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" min="1" max="<?= $product['quantity'] ?>" required>
                    <button type="submit"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                </form>
            </div>
        </div>

        <div class="add-review">
            <h3>Leave a Review</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <label>Rating:</label>
                    <select name="rating" class="stars-select" required>
                        <option value="">-- Select --</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                        <?php endfor; ?>
                    </select>
                    <label>Comment:</label>
                    <textarea name="comment" placeholder="(optional)"></textarea>
                    <button type="submit">Submit Review</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Login</a> to leave a review.</p>
            <?php endif; ?>
        </div>

        <div class="reviews">
            <h3>Product Reviews</h3>
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-item">
                        <strong><?= htmlspecialchars($r['username']) ?></strong> –
                        <span class="stars"><?= str_repeat("⭐", $r['rating']) ?></span>
                        <p><?= htmlspecialchars($r['comment']) ?></p>
                        <small><?= date("F j, Y", strtotime($r['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>© 2025 01 HUB. Where Technology Meets Performance.</p>
    </footer>

</body>

</html>