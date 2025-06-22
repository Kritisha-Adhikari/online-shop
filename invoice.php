<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "online-shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$sql = "SELECT o.id, p.name AS product_name, o.quantity, o.total_price, o.status, o.approval_date
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.id = ? AND o.status = 'Approved'";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2 style='color:red; text-align:center;'>Invoice not available or Order not approved.</h2>";
    exit();
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice - Order #<?= $order['id'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #fff;
            color: #333;
        }

        .invoice-box {
            width: 70%;
            margin: auto;
            border: 2px solid #000;
            padding: 20px;
        }

        h2 {
            text-align: center;
            text-decoration: underline;
        }

        .invoice-details {
            margin-top: 20px;
        }

        .invoice-details p {
            font-size: 16px;
            margin: 8px 0;
        }

        .btn-group {
            margin-top: 30px;
            text-align: center;
        }

        .btn-group button, .btn-group a {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            text-decoration: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px;
        }

        .btn-group button:hover,
        .btn-group a:hover {
            background-color: #0056b3;
        }

        @media print {
            .btn-group {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <h2>INVOICE</h2>

    <div class="invoice-details">
        <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
        <p><strong>Product Name:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
        <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
        <p><strong>Total Price:</strong> Rs. <?= number_format($order['total_price'], 2) ?></p>
        <p><strong>Approval Date:</strong> <?= $order['approval_date'] ?></p>
        <p><strong>Status:</strong> <?= $order['status'] ?></p>
    </div>

    <div class="btn-group">
        <button onclick="window.print()">🖨️ Print Invoice</button>
        <a href="user_orders.php">Back to Orders</a>
    </div>
</div>

</body>
</html>
