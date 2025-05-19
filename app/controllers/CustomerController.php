<?php

class CustomerController extends Controller
{
    public function dashboard()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Redirect if user not logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Fetch user info
        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // Total orders
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $total_orders = $stmt->fetchColumn();

        // Total spent
        $stmt = $pdo->prepare("SELECT SUM(total_price) FROM orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $total_spent = $stmt->fetchColumn() ?: 0;

        // Cart count
        $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

        // Trending products by avg rating
        $stmt = $pdo->query("
            SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating
            FROM products p
            LEFT JOIN product_reviews r ON p.id = r.product_id
            GROUP BY p.id
            ORDER BY avg_rating DESC
            LIMIT 4
        ");
        $topProducts = $stmt->fetchAll();

        // Render dashboard view
        $this->view('dashboard/customer', [
            'user' => $user,
            'total_orders' => $total_orders,
            'total_spent' => $total_spent,
            'cart_count' => $cart_count,
            'topProducts' => $topProducts
        ]);
    }
}
