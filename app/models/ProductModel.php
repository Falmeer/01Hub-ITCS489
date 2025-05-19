<?php
class ProductModel
{
    private $pdo;

    public function __construct()
    {
        require_once '../app/core/Database.php';
        $this->pdo = Database::connect();
    }

    public function getCategories()
    {
        return $this->pdo->query("SELECT * FROM categories")->fetchAll();
    }

    public function searchProducts($search = '', $category = '')
    {
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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getReviewsByProductId($product_id)
    {
        $stmt = $this->pdo->prepare("SELECT r.*, u.username FROM product_reviews r JOIN users u ON u.id = r.user_id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($product_id)
    {
        $stmt = $this->pdo->prepare("SELECT AVG(rating) FROM product_reviews WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return round($stmt->fetchColumn() ?? 0, 1);
    }

    public function getUserReview($review_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM product_reviews WHERE id = ? AND user_id = ?");
        $stmt->execute([$review_id, $user_id]);
        return $stmt->fetch();
    }

    public function updateReview($review_id, $rating, $comment)
    {
        $stmt = $this->pdo->prepare("UPDATE product_reviews SET rating = ?, comment = ? WHERE id = ?");
        return $stmt->execute([$rating, $comment, $review_id]);
    }

    public function insertReview($product_id, $user_id, $rating, $comment)
    {
        $stmt = $this->pdo->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$product_id, $user_id, $rating, $comment]);
    }

    public function hasUserReviewed($product_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM product_reviews WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        return $stmt->fetch();
    }

    public function deleteReview($review_id, $user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM product_reviews WHERE id = ? AND user_id = ?");
        return $stmt->execute([$review_id, $user_id]);
    }
}
