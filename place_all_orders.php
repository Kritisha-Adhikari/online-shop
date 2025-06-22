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

$username = $_SESSION['username'];
$success = true;

foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = $item['quantity'];
    $total_price = $item['price'] * $quantity;

    $stmt = $conn->prepare("INSERT INTO orders (product_id, username, quantity, total_price, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("isid", $product_id, $username, $quantity, $total_price);
    if (!$stmt->execute()) {
        $success = false;
        break;
    }
    $stmt->close();
}

$conn->close();

if ($success) {
    unset($_SESSION['cart']);
    echo "All orders placed successfully! <a href='user_orders.php'>View Orders</a>";
} else {
    echo "Failed to place orders.";
}
?>
