<?php

class ProductsController extends Controller
{
    public function manage()
    {
        session_start();
        require_once '../app/core/Database.php';
        $pdo = Database::connect();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
            header("Location: index.php?url=auth/login");
            exit;
        }

        if (!function_exists('flash')) {
            function flash($message, $type = 'success')
            {
                $_SESSION['flash'] = ['message' => $message, 'type' => $type];
            }
        }

        // Add Product
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            $price = $_POST['price'];
            $qty = $_POST['quantity'];
            $cat = $_POST['category_id'];

            $imageName = '';
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . "." . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $imageName);
            }

            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $desc, $price, $qty, $imageName, $cat]);
            flash("Product added successfully.");
            header("Location: index.php?url=products/manage");
            exit;
        }

        // Edit Product
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
            $id = $_POST['product_id'];
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            $price = $_POST['price'];
            $qty = $_POST['quantity'];
            $cat = $_POST['category_id'];

            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . "." . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $imageName);
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, category_id = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $desc, $price, $qty, $cat, $imageName, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, category_id = ? WHERE id = ?");
                $stmt->execute([$name, $desc, $price, $qty, $cat, $id]);
            }

            flash("Product updated successfully.");
            header("Location: index.php?url=products/manage");
            exit;
        }

        // Delete Product
        if (isset($_GET['delete'])) {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            flash("Product deleted.");
            header("Location: index.php?url=products/manage");
            exit;
        }

        // Filter and Search
        $cats = $pdo->query("SELECT * FROM categories")->fetchAll();
        $filter = $_GET['category'] ?? '';
        $search = $_GET['search'] ?? '';

        $query = "SELECT p.*, c.name AS category FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        if ($filter) {
            $query .= " AND p.category_id = ?";
            $params[] = $filter;
        }

        if (!empty($search)) {
            $query .= " AND p.name LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        $this->view('products/manage', [
            'products' => $products,
            'categories' => $cats,
            'filter' => $filter,
            'flash' => $_SESSION['flash'] ?? null
        ]);

        unset($_SESSION['flash']);
    }
}
