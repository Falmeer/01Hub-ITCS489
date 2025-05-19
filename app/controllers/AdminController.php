<?php

class AdminController extends Controller
{
    public function dashboard()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        // Ensure the user is an admin
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || $user['role'] !== 'admin') {
            header("Location: index.php?url=auth/login");
            exit;
        }

        // Handle new staff creation
        $staffSuccess = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_staff'])) {
            $username = trim($_POST['staff_username']);
            $password = $_POST['staff_password'];
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'staff')");
            $staffSuccess = $stmt->execute([$username, $hashed])
                ? "Supplier account created successfully."
                : "Failed to create supplier account.";
        }

        // Fetch orders
        $orders = $pdo->query("
            SELECT o.id, u.username, o.total_price, o.status, o.created_at 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ")->fetchAll();

        // Fetch reviews
        $reviews = $pdo->query("
            SELECT r.*, p.name AS product_name, u.username 
            FROM product_reviews r 
            JOIN products p ON r.product_id = p.id 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.created_at DESC
        ")->fetchAll();

        // Send data to view
        $this->view('dashboard/admin', [
            'orders' => $orders,
            'reviews' => $reviews,
            'staffSuccess' => $staffSuccess
        ]);
    }
}
