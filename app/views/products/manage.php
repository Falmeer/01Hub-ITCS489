<?php
$products = $data['products'];
$categories = $data['categories'];
$filter = $data['filter'];
$flash = $data['flash'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
        }
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #fff;
            border-right: 1px solid #ddd;
            position: fixed;
            padding: 20px;
        }
        .sidebar img.logo {
            width: 120px;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
            display: flex;
            align-items: center;
        }
        .sidebar ul li i {
            margin-right: 10px;
            color: #00bcd4;
        }
        .sidebar ul li a {
            color: #333;
            text-decoration: none;
        }
        .main {
            margin-left: 260px;
            padding: 40px;
        }
        .table img {
            max-width: 60px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="index.php?url=staff/dashboard">
        <img src="images/01hub-logo.png" class="logo" alt="01 HUB">
    </a>
    <ul>
        <li><i class="fas fa-home"></i><a href="index.php?url=staff/dashboard">Dashboard</a></li>
        <li><i class="fas fa-box"></i><a href="index.php?url=products/manage">Manage Products</a></li>
        <li><i class="fas fa-truck"></i><a href="index.php?url=orders/manage">Manage Orders</a></li>
        <li><i class="fas fa-sign-out-alt"></i><a href="index.php?url=auth/logout">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-box-open me-2"></i>Manage Products</h2>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <form method="GET" class="d-flex">
                <select name="category" class="form-select me-2">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filter == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-outline-secondary">Filter</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" onclick="clearForm()">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>

        <table class="table bg-white table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><img src="images/<?= $p['image'] ?>" alt=""></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= $p['price'] ?> BD</td>
                        <td><?= $p['quantity'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick='editProduct(<?= json_encode($p) ?>)'><i class="fas fa-edit"></i></button>
                            <a href="index.php?url=products/manage&delete=<?= $p['id'] ?>" onclick="return confirm('Delete product?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($products)) echo "<tr><td colspan='6'>No products found.</td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="product_id" id="product_id">
        <input name="name" id="name" class="form-control mb-2" placeholder="Product Name" required>
        <textarea name="description" id="description" class="form-control mb-2" placeholder="Description"></textarea>
        <input name="price" id="price" type="number" step="0.01" class="form-control mb-2" placeholder="Price" required>
        <input name="quantity" id="quantity" type="number" class="form-control mb-2" placeholder="Quantity" required>
        <select name="category_id" id="category_id" class="form-select mb-2" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_product" id="addBtn" class="btn btn-primary">Add</button>
        <button type="submit" name="edit_product" id="editBtn" class="btn btn-warning d-none">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
function editProduct(p) {
    document.getElementById('modalTitle').textContent = "Edit Product";
    document.getElementById('product_id').value = p.id;
    document.getElementById('name').value = p.name;
    document.getElementById('description').value = p.description;
    document.getElementById('price').value = p.price;
    document.getElementById('quantity').value = p.quantity;
    document.getElementById('category_id').value = p.category_id;
    document.getElementById('addBtn').classList.add('d-none');
    document.getElementById('editBtn').classList.remove('d-none');
    new bootstrap.Modal(document.getElementById('productModal')).show();
}
function clearForm() {
    document.getElementById('modalTitle').textContent = "Add Product";
    document.getElementById('product_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('price').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('category_id').value = '';
    document.getElementById('addBtn').classList.remove('d-none');
    document.getElementById('editBtn').classList.add('d-none');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
