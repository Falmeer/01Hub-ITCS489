<?php $categories = $data['categories']; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Products - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: #fff;
            border-right: 1px solid #ddd;
            position: fixed;
            padding: 20px;
        }

        .sidebar img.logo {
            width: 120px;
            margin-bottom: 30px;
            cursor: pointer;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
            font-size: 16px;
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
            padding: 20px 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .topbar select,
        .topbar input[type="text"] {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .product-grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: 180px;
            object-fit: contain;
        }

        .product-card h3 {
            font-size: 18px;
            margin: 10px 0 5px;
        }

        .product-card p.price {
            color: #2980b9;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-card a.button {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #00bcd4;
            color: white;
            text-decoration: none;
            margin: 5px 5px 0 5px;
            font-size: 14px;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 15px;
            color: #888;
        }

        .button-cart {
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .button-cart:hover {
            background-color: #009cb3;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a href="index.php?url=customer/dashboard">
            <img src="images/01hub-logo.png" alt="01 HUB Logo" class="logo">
        </a>
        <ul>
            <li><i class="fas fa-home"></i> <a href="index.php?url=customer/dashboard">Home</a></li>
            <li><i class="fas fa-box"></i> <a href="index.php?url=product/browse">Products</a></li>
            <li><i class="fas fa-shopping-cart"></i> <a href="index.php?url=cart/index">Cart</a></li>
            <li><i class="fas fa-sign-out-alt"></i> <a href="index.php?url=auth/logout">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <div>
                <h1 style="display:inline-block; margin-right: 20px;">Products</h1>
                <select id="categorySelect">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" id="searchInput" placeholder="Search product...">
            </div>
        </div>

        <div id="productContainer" class="product-grid">
            <!-- Products will load here -->
        </div>
    </div>

    <footer>
        <p>© 2025 01 HUB. Where Technology Meets Performance.</p>
    </footer>

    <script>
        function loadProducts() {
            const search = $('#searchInput').val();
            const category = $('#categorySelect').val();

            $.ajax({
                url: 'index.php?url=product/search',
                method: 'GET',
                data: { search, category },
                success: function (data) {
                    $('#productContainer').html(data);
                }
            });
        }

        $(document).ready(function () {
            loadProducts();
            $('#searchInput').on('keyup', loadProducts);
            $('#categorySelect').on('change', loadProducts);
        });
    </script>

</body>
</html>
