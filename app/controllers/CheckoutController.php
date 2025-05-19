<?php

class CheckoutController extends Controller
{
    public function process()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        require_once '../app/models/CheckoutModel.php';
        $model = new CheckoutModel();

        $user_id = $_SESSION['user_id'];
        $cart = $_SESSION['cart'];
        $total_price = 0;

        try {
            $model->beginTransaction();

            // Check stock and calculate total
            foreach ($cart as $product_id => $quantity) {
                $product = $model->getProductStockAndPrice($product_id);

                if (!$product || $product['quantity'] < $quantity) {
                    $_SESSION['checkout_error'] = "Insufficient stock for '{$product['name']}'";
                    header("Location: index.php?url=cart/index");
                    exit;
                }

                $total_price += $product['price'] * $quantity;
            }

            $model->createCheckoutLog($user_id, $total_price);
            $order_id = $model->createOrder($user_id, $total_price);

            foreach ($cart as $product_id => $quantity) {
                $model->insertOrderItem($order_id, $product_id, $quantity);
                $model->reduceProductStock($product_id, $quantity);
            }

            $model->commit();
            unset($_SESSION['cart']);
            $_SESSION['order_success'] = true;
            header("Location: index.php?url=checkout/thank_you");
            exit;

        } catch (Exception $e) {
            $model->rollBack();
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