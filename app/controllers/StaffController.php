<?php

class StaffController extends Controller
{
    public function dashboard()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Ensure staff is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        $this->view('dashboard/staff', ['user' => $user]);
    }
}
