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

// order_id GET बाट लिन्छौ, नत्र session message प्रयोग गर्छौ
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$message = "";
$msg_class = "";
$total_price = "";
$date_label = "";
$date_value = "";

// DB बाट order info लिने कोशिश
if ($order_id > 0) {
    $sql = "SELECT status, total_price, approval_date, rejection_date FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        if ($order['status'] == 'approved') {
            $message = "Order Approved Successfully!";
            $msg_class = "success";
            $date_label = "Approved Date:";
            $date_value = $order['approval_date'];
        } elseif ($order['status'] == 'rejected') {
            $message = "Order Rejected.";
            $msg_class = "error";
            $date_label = "Rejected Date:";
            $date_value = $order['rejection_date'];
        } else {
            $message = "Order is pending approval.";
            $msg_class = "pending";
        }
        $total_price = $order['total_price'];
    } else {
        $message = "Order not found.";
        $msg_class = "error";
    }
    $stmt->close();
    $conn->close();
} else {
    // Session message fallback
    if (isset($_SESSION['order_success'])) {
        $message = $_SESSION['order_success'];
        $msg_class = "success";
        unset($_SESSION['order_success']);
    } elseif (isset($_SESSION['order_error'])) {
        $message = $_SESSION['order_error'];
        $msg_class = "error";
        unset($_SESSION['order_error']);
    } else {
        $message = "No order result found.";
        $msg_class = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Result</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 100px; background: #f5f5f5; }
        .success { color: green; font-size: 24px; }
        .error { color: red; font-size: 24px; }
        .pending { color: orange; font-size: 24px; }
        .details { margin-top: 20px; font-size: 18px; }
        a { text-decoration: none; color: #007bff; font-size: 18px; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <p class="<?= htmlspecialchars($msg_class) ?>"><?= htmlspecialchars($message) ?></p>

    <?php if (!empty($total_price)): ?>
        <div class="details">
            <p><strong>Total Price:</strong> Rs. <?= number_format($total_price, 2) ?></p>
            <?php if ($date_label && $date_value): ?>
                <p><strong><?= htmlspecialchars($date_label) ?></strong> <?= htmlspecialchars($date_value) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <br>
    <a href="view_products_user.php">Back to Products</a>
</body>
</html>
