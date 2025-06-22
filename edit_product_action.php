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

$id = intval($_POST['id']);
$name = $conn->real_escape_string($_POST['name']);
$description = $conn->real_escape_string($_POST['description']);
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);

$sql = "UPDATE products SET name='$name', description='$description', price=$price, quantity=$quantity WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: view_products.php");
    exit();
} else {
    echo "Error updating product: " . $conn->error;
}

$conn->close();
?>
