<?php

class CheckoutController extends Controller
{
    public function process()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $cart = $_SESSION['cart'];
        $total_price = 0;

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Validate stock and calculate total
            foreach ($cart as $product_id => $quantity) {
                $stmt = $pdo->prepare("SELECT name, price, quantity FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch();

                if (!$product || $product['quantity'] < $quantity) {
                    $_SESSION['checkout_error'] = "Insufficient stock for '{$product['name']}'";
                    header("Location: index.php?url=cart/index");
                    exit;
                }

                $total_price += $product['price'] * $quantity;
            }

            // Log checkout
            $stmt = $pdo->prepare("INSERT INTO checkout (user_id, total_price) VALUES (?, ?)");
            $stmt->execute([$user_id, $total_price]);

            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
            $stmt->execute([$user_id, $total_price]);
            $order_id = $pdo->lastInsertId();

            // Order items and reduce stock
            foreach ($cart as $product_id => $quantity) {
                // Insert item
                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, (SELECT price FROM products WHERE id = ?))
                ");
                $stmt->execute([$order_id, $product_id, $quantity, $product_id]);

                // Update inventory
                $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
                $stmt->execute([$quantity, $product_id, $quantity]);
            }

            $pdo->commit();
            unset($_SESSION['cart']);
            $_SESSION['order_success'] = true;

            header("Location: index.php?url=checkout/thank_you");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['checkout_error'] = "An error occurred while processing your order.";
            header("Location: index.php?url=cart/index");
            exit;
        }
    }

    public function thank_you()
    {
        session_start();
        if (!isset($_SESSION['order_success'])) {
            header("Location: index.php?url=cart/index");
            exit;
        }

        unset($_SESSION['order_success']);
        $this->view('checkout/thank_you');
    }
}
