<?php

class ProductController extends Controller
{
    public function browse()
    {
        require_once '../app/core/Database.php';
        $pdo = Database::connect();
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
        $this->view('products/browse', ['categories' => $categories]);
    }

    public function search()
    {
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';

        $sql = "SELECT * FROM products WHERE 1";
        $params = [];

        if ($category) {
            $sql .= " AND category_id = ?";
            $params[] = $category;
        }

        if ($search) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        foreach ($products as $p) {
            echo "
            <div class='product-card'>
                <img src='images/{$p['image']}' alt='{$p['name']}'>
                <h3>{$p['name']}</h3>
                <p class='price'>{$p['price']} BD</p>
                <a href='index.php?url=product/detail&id={$p['id']}' class='button'>View</a>
                <form method='POST' action='index.php?url=cart/add' style='display:inline;'>
                    <input type='hidden' name='product_id' value='{$p['id']}'>
                    <input type='hidden' name='quantity' value='1'>
                    <button type='submit' class='button-cart'><i class='fas fa-cart-plus'></i> Add to Cart</button>
                </form>
            </div>";
        }

        if (empty($products)) {
            echo "<p>No products found.</p>";
        }
    }

    public function detail()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?url=products/browse");
            exit;
        }

        // Fetch product
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            header("Location: index.php?url=products/browse");
            exit;
        }

        // Fetch reviews
        $stmt = $pdo->prepare("SELECT r.*, u.username FROM product_reviews r JOIN users u ON u.id = r.user_id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$id]);
        $reviews = $stmt->fetchAll();

        // Average rating
        $stmt = $pdo->prepare("SELECT AVG(rating) FROM product_reviews WHERE product_id = ?");
        $stmt->execute([$id]);
        $avg_rating_raw = $stmt->fetchColumn();
        $avg_rating = round($avg_rating_raw ?? 0, 1);
        $user_id = $_SESSION['user_id'] ?? null;
        $editingReview = null;

        // Handle review update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user_id)) {
            if (isset($_POST['update_review']) && isset($_POST['review_id'])) {
                $review_id = $_POST['review_id'];
                $rating = max(1, min(5, intval($_POST['rating'])));
                $comment = trim($_POST['comment']);

                // Make sure the review belongs to the user
                $stmt = $pdo->prepare("SELECT * FROM product_reviews WHERE id = ? AND user_id = ?");
                $stmt->execute([$review_id, $user_id]);
                $existing = $stmt->fetch();

                if ($existing) {
                    $stmt = $pdo->prepare("UPDATE product_reviews SET rating = ?, comment = ? WHERE id = ?");
                    $stmt->execute([$rating, $comment, $review_id]);
                }

                header("Location: index.php?url=product/detail&id=$id");
                exit;
            }

            // New review (if not already exists)
            if (isset($_POST['rating']) && !isset($_POST['review_id'])) {
                $rating = max(1, min(5, intval($_POST['rating'])));
                $comment = trim($_POST['comment']);

                $check = $pdo->prepare("SELECT id FROM product_reviews WHERE user_id = ? AND product_id = ?");
                $check->execute([$user_id, $id]);

                if (!$check->fetch()) {
                    $stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$id, $user_id, $rating, $comment]);
                }

                header("Location: index.php?url=product/detail&id=$id");
                exit;
            }
        }

        // Handle edit request
        if (isset($_GET['edit']) && $user_id) {
            $edit_id = $_GET['edit'];
            $stmt = $pdo->prepare("SELECT * FROM product_reviews WHERE id = ? AND user_id = ?");
            $stmt->execute([$edit_id, $user_id]);
            $editingReview = $stmt->fetch();
        }

        // Handle delete request
        if (isset($_GET['delete']) && $user_id) {
            $del_id = $_GET['delete'];
            $stmt = $pdo->prepare("DELETE FROM product_reviews WHERE id = ? AND user_id = ?");
            $stmt->execute([$del_id, $user_id]);

            header("Location: index.php?url=product/detail&id=$id");
            exit;
        }

        $this->view('products/detail', [
            'product' => $product,
            'reviews' => $reviews,
            'avg_rating' => $avg_rating,
            'editingReview' => $editingReview
        ]);
    }
}
