<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            header("Location: customer_dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register - 01 HUB</title>
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
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #00bcd4;
            margin-bottom: 20px;
        }

        input,
        button {
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
            transition: background 0.3s;
        }

        button:hover {
            background: #009cb3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .link a {
            color: #00bcd4;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Register</h2>

        <?php if ($errors): ?>
            <div class="error">
                <?php foreach ($errors as $e)
                    echo "<p>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password (min 6 chars)" required>

            <input type="password" name="confirm" placeholder="Confirm Password" required>

            <button type="submit">Register</button>
        </form>

        <div class="link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>

</body>

</html>