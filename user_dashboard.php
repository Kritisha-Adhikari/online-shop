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
    
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p><a href="view_products_user.php">📦 View Products</a></p>
    <p><a href="user_orders.php">📝 My Orders</a></p>
    <p><a href="logout.php">🚪 Logout</a></p>
</body>

</html>
