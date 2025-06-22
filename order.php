<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
</head>
<body>
    <h2>Place an Order</h2>
    <form action="place_order.php" method="POST">
        <label>Product ID:</label>
        <input type="number" name="product_id" required><br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" required><br><br>

        <input type="submit" value="Place Order">
    </form>
</body>
</html>
