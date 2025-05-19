<?php

class OrdersController extends Controller
{
    public function manage()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Only staff allowed
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
            header("Location: index.php?url=auth/login");
            exit;
        }

        // Flash helper
        if (!function_exists('flash')) {
            function flash($msg, $type = 'success') {
                $_SESSION['flash'] = ['message' => $msg, 'type' => $type];
            }
        }

        // Handle status update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];

            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $orderId]);
            flash("Order #$orderId updated to '$status'");
            header("Location: index.php?url=orders/manage");
            exit;
        }

        // Fetch orders
        $stmt = $pdo->query("
            SELECT o.*, u.username
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll();

        // Load view
        $this->view('orders/manage', [
            'orders' => $orders,
            'flash' => $_SESSION['flash'] ?? null
        ]);

        unset($_SESSION['flash']);
    }
}
