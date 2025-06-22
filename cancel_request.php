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

$order_id = $_POST['order_id'] ?? 0;
$username = $_SESSION['username'];

// Check if the order belongs to the logged-in user and is still pending
$sql = "SELECT * FROM orders WHERE id = ? AND username = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $order_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Update status to Cancel Requested
    $update = $conn->prepare("UPDATE orders SET status = 'Cancel Requested' WHERE id = ?");
    $update->bind_param("i", $order_id);
    if ($update->execute()) {
        $_SESSION['order_success'] = "Cancel request sent successfully.";
    } else {
        $_SESSION['order_error'] = "Error updating order status.";
    }
    $update->close();
} else {
    $_SESSION['order_error'] = "You cannot cancel this order.";
}

$stmt->close();
$conn->close();

header("Location: user_orders.php");
exit();
?>
