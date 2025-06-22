<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "online-shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        echo "<p>Order <strong>$status</strong> successfully!</p>";
        echo '<p><a href="manage_orders.php">Back to Manage Orders</a></p>';
        $stmt->close();
    } else {
        echo "Error in prepare statement: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
