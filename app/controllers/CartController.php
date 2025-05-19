<?php

class CartController extends Controller
{
    public function index()
    {
        session_start();

        // 🔧 Handle legacy or corrupted cart format
        if (!empty($_SESSION['cart']) && is_array(current($_SESSION['cart'])) && isset(current($_SESSION['cart'])['product'])) {
            unset($_SESSION['cart']);
        }

        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $total_price = 0;

        foreach ($cart as $product_id => $quantity) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            if ($product) {
                $subtotal = $product['price'] * $quantity;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $total_price += $subtotal;
            }
        }

        $this->view('cart/index', [
            'items' => $items,
            'total_price' => $total_price
        ]);
    }

    public function add()
    {
        session_start();

        if (!isset($_POST['product_id'], $_POST['quantity'])) {
            header("Location: index.php?url=product/browse");
            exit;
        }

        $product_id = intval($_POST['product_id']);
        $quantity = max(1, intval($_POST['quantity']));

        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        // Ensure product exists
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        if (!$stmt->fetch()) {
            header("Location: index.php?url=product/browse");
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }

        header("Location: index.php?url=cart/index");
        exit;
    }

    public function remove()
    {
        session_start();
        $id = $_POST['product_id'] ?? null;

        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        header("Location: index.php?url=cart/index");
        exit;
    }
}
