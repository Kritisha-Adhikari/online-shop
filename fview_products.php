<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "online-shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }
        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>

    <a href="product_add.php">Add New Product</a><br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($product = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['id']) . "</td>";
                echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                echo "<td>" . htmlspecialchars($product['description']) . "</td>";
                echo "<td>" . htmlspecialchars($product['price']) . "</td>";
                echo "<td>" . htmlspecialchars($product['quantity']) . "</td>";
                echo "<td>
                        <a href='edit_product.php?id=" . $product['id'] . "'>Edit</a> | 
                        <a href='delete_product.php?id=" . $product['id'] . "' onclick=\"return confirm('Are you sure to delete this product?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
