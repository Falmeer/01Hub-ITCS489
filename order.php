<!-- order.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Place Order</h2>
        <form action="place_order.php" method="POST">
            <!-- Render product options dynamically from database -->
            <?php
            require 'db.php';
            $stmt = $pdo->query("SELECT * FROM products");
            $products = $stmt->fetchAll();
            foreach ($products as $product) {
                echo '<label>';
                echo '<input type="checkbox" name="products[]" value="' . $product['id'] . '">';
                echo $product['name'] . ' - $' . $product['price'];
                echo '</label><br>';
            }
            ?>
            <label for="address">Delivery Address:</label>
            <textarea name="address" id="address" required></textarea><br>
            <button type="submit">Place Order</button>
        </form>
    </div>
</body>
</html>
