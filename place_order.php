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

$product_id = $_POST['product_id'] ?? null;
$order_quantity = $_POST['order_quantity'] ?? null;
$username = $_SESSION['username'];

if (!$product_id || !$order_quantity || $order_quantity <= 0) {
    $_SESSION['order_error'] = "Invalid order data. Please try again.";
    header("Location: order_result.php");
    exit();
}

// Step 1: Get price and quantity from products table
$stock_check_sql = "SELECT price, quantity FROM products WHERE id = ?";
$stmt = $conn->prepare($stock_check_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($price, $available_quantity);
$stmt->fetch();
$stmt->close();

if ($order_quantity > $available_quantity) {
    $_SESSION['order_error'] = "Sorry, only $available_quantity units available in stock.";
    header("Location: order_result.php");
    exit();
}

// Step 2: Calculate total price
$total_price = $price * $order_quantity;

// Step 3: Insert order into orders table
$sql = "INSERT INTO orders (product_id, username, quantity, total_price, status) VALUES (?, ?, ?, ?, 'Pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isid", $product_id, $username, $order_quantity, $total_price);

if ($stmt->execute()) {
    // Step 4: Reduce stock in products table
    $update_sql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $order_quantity, $product_id);
    $update_stmt->execute();
    $update_stmt->close();

    $_SESSION['order_success'] = "Order placed successfully!";
} else {
    $_SESSION['order_error'] = "Error placing order: " . $conn->error;
}

$stmt->close();
$conn->close();

header("Location: order_result.php");
exit();
?>
