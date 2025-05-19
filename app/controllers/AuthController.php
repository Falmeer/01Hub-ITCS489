<?php

class AuthController extends Controller
{
    public function login()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        $error = '';
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $remember = isset($_POST['remember']);

            if (empty($username) || empty($password)) {
                $error = "Please fill in all fields.";
            } else {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];

                    if ($remember) {
                        setcookie("username", $username, time() + (86400 * 30), "/");
                    }

                    switch ($user['role']) {
                        case 'admin':
                            header("Location: index.php?url=admin/dashboard");
                            break;
                        case 'staff':
                            header("Location: index.php?url=staff/dashboard");
                            break;
                        default:
                            header("Location: index.php?url=customer/dashboard");
                            break;
                    }
                    exit;
                } else {
                    $error = "Invalid username or password.";
                }
            }
        }

        if (isset($_COOKIE['username'])) {
            $username = $_COOKIE['username'];
        }

        $this->view('auth/login', ['error' => $error, 'username' => $username]);
    }

    public function register()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];

            if (empty($username) || empty($password) || empty($confirm)) {
                $errors[] = "All fields are required.";
            } elseif ($password !== $confirm) {
                $errors[] = "Passwords do not match.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters.";
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $errors[] = "Username already exists.";
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'customer')");
                    $stmt->execute([$username, $hash]);
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['role'] = 'customer';
                    header("Location: index.php?url=customer/dashboard");
                    exit;
                }
            }
        }

        $this->view('auth/register', ['errors' => $errors]);
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
