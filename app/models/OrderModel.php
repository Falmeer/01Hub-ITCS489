<?php

class OrderModel extends Model
{
    public function getUserOrders($user_id)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getOrderItems($order_id)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("
            SELECT p.name, oi.quantity, oi.price
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }

    private function db()
    {
        require_once '../app/core/Database.php';
        return Database::connect();
    }
}
