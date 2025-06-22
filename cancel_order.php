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
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    $_SESSION['order_error'] = "Invalid order ID.";
    header("Location: user_orders.php");
    exit();
}

// Check if order belongs to this user and is still Pending
$sql = "SELECT * FROM orders WHERE id = ? AND username = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $order_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Cancel the order
    $update = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
    $update->bind_param("i", $order_id);
    if ($update->execute()) {
        $_SESSION['order_success'] = "Order cancelled successfully.";
    } else {
        $_SESSION['order_error'] = "Failed to cancel order.";
    }
    $update->close();
} else {
    $_SESSION['order_error'] = "Order not found or not eligible for cancellation.";
}

$stmt->close();
$conn->close();

header("Location: user_orders.php");
exit();
?>
