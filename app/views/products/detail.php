<?php
$product = $data['product'];
$reviews = $data['reviews'];
$avg_rating = $data['avg_rating'];
$editingReview = $data['editingReview'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$hasReviewed = false;
foreach ($reviews as $r) {
    if ($user_id && $r['user_id'] == $user_id) {
        $hasReviewed = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']) ?> - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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
        select,
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #00bcd4;
            color: white;
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

        .review-item {
            border-top: 1px solid #eee;
            padding: 15px 0;
        }

        .review-item:first-child {
            border-top: none;
        }

        .review-item .stars {
            color: #f39c12;
        }

        .review-item a {
            margin-right: 10px;
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
    <a href="index.php?url=customer/dashboard">
        <img src="images/01hub-logo.png" class="logo" alt="01 HUB">
    </a>
    <ul>
        <li><i class="fas fa-home"></i><a href="index.php?url=customer/dashboard">Home</a></li>
        <li><i class="fas fa-box"></i><a href="index.php?url=product/browse">Products</a></li>
        <li><i class="fas fa-shopping-cart"></i><a href="index.php?url=cart/index">Cart</a></li>
        <li><i class="fas fa-sign-out-alt"></i><a href="index.php?url=auth/logout">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="product-detail-card">
        <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <p class="price"><?= $product['price'] ?> BD</p>
            <p>Available: <?= $product['quantity'] ?></p>
            <p>Average Rating: ⭐ <?= $avg_rating ?> / 5</p>

            <form method="POST" action="index.php?url=cart/add">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label>Quantity:</label>
                <input type="number" name="quantity" min="1" max="<?= $product['quantity'] ?>" required>
                <button type="submit"><i class="fas fa-cart-plus"></i> Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="add-review">
        <h3><?= $editingReview ? "Edit Your Review" : "Leave a Review" ?></h3>

        <?php if ($user_id): ?>
            <?php if ($editingReview): ?>
                <form method="POST">
                    <input type="hidden" name="review_id" value="<?= $editingReview['id'] ?>">
                    <label>Rating:</label>
                    <select name="rating" required>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>" <?= $editingReview['rating'] == $i ? 'selected' : '' ?>><?= $i ?> ⭐</option>
                        <?php endfor; ?>
                    </select>
                    <label>Comment:</label>
                    <textarea name="comment"><?= htmlspecialchars($editingReview['comment']) ?></textarea>
                    <button type="submit" name="update_review">Update Review</button>
                    <a href="index.php?url=product/detail&id=<?= $product['id'] ?>" style="margin-left:10px; color:#888;">Cancel</a>
                </form>
            <?php elseif ($hasReviewed): ?>
                <p style="color:gray;">You already reviewed this product.</p>
            <?php else: ?>
                <form method="POST">
                    <label>Rating:</label>
                    <select name="rating" required>
                        <option value="">-- Select --</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                        <?php endfor; ?>
                    </select>
                    <label>Comment:</label>
                    <textarea name="comment" placeholder="(optional)"></textarea>
                    <button type="submit">Submit Review</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p><a href="index.php?url=auth/login">Login</a> to leave a review.</p>
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
                    <?php if ($user_id && $r['user_id'] == $user_id): ?>
                        <div>
                            <a href="index.php?url=product/detail&id=<?= $product['id'] ?>&edit=<?= $r['id'] ?>" style="color:#00bcd4;">Edit</a>
                            <a href="index.php?url=product/detail&id=<?= $product['id'] ?>&delete=<?= $r['id'] ?>" onclick="return confirm('Delete review?')" style="color:red;">Delete</a>
                        </div>
                    <?php endif; ?>
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