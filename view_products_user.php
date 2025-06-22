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

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        h2 { text-align: center; }
        input[type=number] { width: 60px; }
    </style>
</head>
<body>
    <h2>Available Products</h2>
    <p style="text-align:center;"><a href="cart.php">🛒 View Cart</a></p>

    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price (Rs.)</th>
            <th>Available Quantity</th>
            <th>Order Quantity</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>
                <form method="POST" action="add_to_cart.php">
                 <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                 <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                 <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                 <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['quantity']; ?>" required>
</td>
<td>
    <input type="submit" value="Add to Cart">
</form>

            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
