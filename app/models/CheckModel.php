<?php

class CheckoutModel extends Model
{
    private function db()
    {
        require_once '../app/core/Database.php';
        return Database::connect();
    }

    public function getProductStockAndPrice($product_id)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("SELECT name, price, quantity FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetch();
    }

    public function createCheckoutLog($user_id, $total_price)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("INSERT INTO checkout (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_price]);
    }

    public function createOrder($user_id, $total_price)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_price]);
        return $pdo->lastInsertId();
    }

    public function insertOrderItem($order_id, $product_id, $quantity)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, (SELECT price FROM products WHERE id = ?))
        ");
        $stmt->execute([$order_id, $product_id, $quantity, $product_id]);
    }

    public function reduceProductStock($product_id, $quantity)
    {
        $pdo = $this->db();
        $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
        $stmt->execute([$quantity, $product_id, $quantity]);
    }

    public function beginTransaction()
    {
        $this->db()->beginTransaction();
    }

    public function commit()
    {
        $this->db()->commit();
    }

    public function rollBack()
    {
        $this->db()->rollBack();
    }
}
