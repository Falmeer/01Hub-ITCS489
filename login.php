<?php
session_start();
require 'db.php';

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
                setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
            }

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header('Location: admin_dashboard.php');
                    break;
                case 'staff':
                    header('Location: staff_dashboard.php');
                    break;
                default:
                    header('Location: customer_dashboard.php');
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - 01 HUB</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #00bcd4;
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 8px;
            box-sizing: border-box;
        }
        input {
            border: 1px solid #ccc;
        }
        button {
            background: #00bcd4;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover {
            background: #009cb3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
        }
        .link {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }
        .link a {
            color: #00bcd4;
            text-decoration: none;
        }
        .remember {
            display: flex;
            align-items: center;
            margin: 10px 0;
            font-size: 14px;
        }
        .remember input {
            width: auto;
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="remember">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="link">
        Don't have an account? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>
