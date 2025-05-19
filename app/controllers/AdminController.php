<?php

class AdminController extends Controller
{
    public function dashboard()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Check if logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        // Check if user is admin
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || $user['role'] !== 'admin') {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $staffSuccess = "";

        // Handle creation of new staff account
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_staff'])) {
            $username = trim($_POST['staff_username']);
            $password = $_POST['staff_password'];

            if (empty($username) || empty($password)) {
                $staffSuccess = "❌ Username and password are required.";
            } else {
                // Check if username exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);

                if ($stmt->fetch()) {
                    $staffSuccess = "❌ Username already exists.";
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'staff')");
                    if ($stmt->execute([$username, $hashed])) {
                        $staffSuccess = "✅ Supplier account created successfully.";
                    } else {
                        $staffSuccess = "❌ Failed to create supplier account.";
                    }
                }
            }
        }

        // Fetch orders
        $orders = $pdo->query("
            SELECT o.id, u.username, o.total_price, o.status, o.created_at 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ")->fetchAll();

        // Fetch product reviews
        $reviews = $pdo->query("
            SELECT r.*, p.name AS product_name, u.username 
            FROM product_reviews r 
            JOIN products p ON r.product_id = p.id 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.created_at DESC
        ")->fetchAll();

        // Pass everything to view
        $this->view('dashboard/admin', [
            'orders' => $orders,
            'reviews' => $reviews,
            'staffSuccess' => $staffSuccess
        ]);
    }
}