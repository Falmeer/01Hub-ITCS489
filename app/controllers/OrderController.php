<?php

class OrderController extends Controller
{
    public function history()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        require_once '../app/models/OrderModel.php';
        $model = new OrderModel();
        $user_id = $_SESSION['user_id'];

        $orders = $model->getUserOrders($user_id);

        foreach ($orders as &$order) {
            $order['items'] = $model->getOrderItems($order['id']);
        }

        $this->view('orders/history', ['orders' => $orders]);
    }
}
