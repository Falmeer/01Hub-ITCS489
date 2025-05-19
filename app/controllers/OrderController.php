<?php

class OrderController extends Controller
{
    public function history()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Redirect to login if not authenticated
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Fetch user's orders (latest first)
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll();

        // Attach ordered items for each order
        foreach ($orders as &$order) {
            $stmt = $pdo->prepare("
                SELECT p.name, oi.quantity, oi.price
                FROM order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = ?
            ");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();
        }

        // Render view with order data
        $this->view('orders/history', ['orders' => $orders]);
    }
}
