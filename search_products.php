<?php
require 'db.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND name LIKE ?";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND category_id = ?";
    $params[] = $category;
}

$query .= " ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

if (empty($products)) {
    echo "<p style='grid-column: 1 / -1; text-align: center; color: #888;'>No products found.</p>";
}

foreach ($products as $p): ?>
    <div class="product-card">
        <img src="images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p class="price"><?= number_format($p['price'], 2) ?> BD</p>
       <form method="POST" action="add_to_cart.php" style="display: flex; gap: 10px; justify-content: center; margin-top: 10px;">
    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
    <input type="hidden" name="quantity" value="1">
    <a class="button-cart" href="product_detail.php?id=<?= $p['id'] ?>"><i class="fas fa-eye"></i> View</a>
    <button type="submit" class="button-cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
</form>
    </div>
<?php endforeach; ?>
