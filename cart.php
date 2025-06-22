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
    <title>My Cart</title>
    <style>
        table { width: 80%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        h2, p { text-align: center; }
    </style>
</head>
<body>
    <h2>My Shopping Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form method="POST" action="place_all_orders.php">
            <table>
                <tr>
    <th>Product</th>
    <th>Price (Rs.)</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Action</th> <!-- Add this -->
</tr>

                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $item):

                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
    <td><?= htmlspecialchars($item['name']) ?></td>
    <td><?= number_format($item['price'], 2) ?></td>
    <td><?= $item['quantity'] ?></td>
    <td><?= number_format($subtotal, 2) ?></td>
    <td><a href="remove_from_cart.php?index=<?= $index ?>" onclick="return confirm('Remove this item?');">Remove</a></td>
</tr>

            </table>
            <br>
            <div style="text-align: center;">
                <input type="submit" value="Place All Orders">
            </div>
        </form>
    <?php endif; ?>

    <p style="text-align: center;"><a href="view_products_user.php">Back to Products</a></p>
</body>
</html>
