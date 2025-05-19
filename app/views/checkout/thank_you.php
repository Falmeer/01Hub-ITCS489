<!DOCTYPE html>
<html>

<head>
    <title>Thank You - 01 HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .thankyou-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .thankyou-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .thankyou-box i {
            font-size: 60px;
            color: #00bcd4;
            margin-bottom: 20px;
        }

        .thankyou-box h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
        }

        .thankyou-box p {
            color: #666;
            margin-bottom: 30px;
        }

        .thankyou-box a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #00bcd4;
            color: #fff;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .thankyou-box a:hover {
            background-color: #009cb3;
        }
    </style>
</head>

<body>

    <div class="thankyou-container">
        <div class="thankyou-box">
            <i class="fas fa-check-circle"></i>
            <h1>Thank You!</h1>
            <p>Your order has been placed successfully.</p>
            <a href="index.php?url=order/history"><i class="fas fa-box-open"></i> View Order History</a>
        </div>
    </div>

</body>

</html>
