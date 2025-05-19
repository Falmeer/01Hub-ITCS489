<?php

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        require_once '../app/models/ProductModel.php';
        $this->productModel = new ProductModel();
    }

    public function browse()
    {
        $categories = $this->productModel->getCategories();
        $this->view('products/browse', ['categories' => $categories]);
    }

    public function search()
    {
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';

        $products = $this->productModel->searchProducts($search, $category);

        foreach ($products as $p) {
            echo "
            <div class='product-card'>
                <img src='images/{$p['image']}' alt='{$p['name']}'>
                <h3>{$p['name']}</h3>
                <p class='price'>{$p['price']} BD</p>
                <a href='index.php?url=product/detail&id={$p['id']}' class='button'>View</a>
                <form method='POST' action='index.php?url=cart/add' style='display:inline;'>
                    <input type='hidden' name='product_id' value='{$p['id']}'>
                    <input type='hidden' name='quantity' value='1'>
                    <button type='submit' class='button-cart'><i class='fas fa-cart-plus'></i> Add to Cart</button>
                </form>
            </div>";
        }

        if (empty($products)) {
            echo "<p>No products found.</p>";
        }
    }

    public function detail()
    {
        session_start();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?url=products/browse");
            exit;
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header("Location: index.php?url=products/browse");
            exit;
        }

        $reviews = $this->productModel->getReviewsByProductId($id);
        $avg_rating = $this->productModel->getAverageRating($id);
        $user_id = $_SESSION['user_id'] ?? null;
        $editingReview = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
            if (isset($_POST['update_review'], $_POST['review_id'])) {
                $existing = $this->productModel->getUserReview($_POST['review_id'], $user_id);
                if ($existing) {
                    $this->productModel->updateReview($_POST['review_id'], $_POST['rating'], trim($_POST['comment']));
                }
                header("Location: index.php?url=product/detail&id=$id");
                exit;
            }

            if (isset($_POST['rating']) && !isset($_POST['review_id'])) {
                if (!$this->productModel->hasUserReviewed($id, $user_id)) {
                    $this->productModel->insertReview($id, $user_id, $_POST['rating'], trim($_POST['comment']));
                }
                header("Location: index.php?url=product/detail&id=$id");
                exit;
            }
        }

        if (isset($_GET['edit']) && $user_id) {
            $editingReview = $this->productModel->getUserReview($_GET['edit'], $user_id);
        }

        if (isset($_GET['delete']) && $user_id) {
            $this->productModel->deleteReview($_GET['delete'], $user_id);
            header("Location: index.php?url=product/detail&id=$id");
            exit;
        }

        $this->view('products/detail', [
            'product' => $product,
            'reviews' => $reviews,
            'avg_rating' => $avg_rating,
            'editingReview' => $editingReview
        ]);
    }
}
