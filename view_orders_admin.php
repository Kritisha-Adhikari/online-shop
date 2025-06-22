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

$status_filter = "";
if (isset($_GET['status']) && $_GET['status'] != 'All') {
    $selected = $conn->real_escape_string($_GET['status']);
    $status_filter = "WHERE orders.status = '$selected'";
}

$status_filter = "";
if (isset($_GET['status']) && $_GET['status'] != 'All') {
    $selected_status = $conn->real_escape_string($_GET['status']);
    $status_filter = "WHERE orders.status = '$selected_status'";
}

$sql = "SELECT orders.id, products.name AS product_name, orders.username, orders.quantity, orders.status
        FROM orders
        JOIN products ON orders.product_id = products.id
        $status_filter";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        h2 { text-align: center; }
        form { display: inline-block; margin: 2px; }
    </style>
</head>
<body>
    <h2>Manage Orders</h2>
    <form method="GET" action="view_orders_admin.php" style="text-align: center; margin: 20px;">
    <label for="status">Filter by Status:</label>
    <select name="status" onchange="this.form.submit()">
        <option value="All" <?= (!isset($_GET['status']) || $_GET['status'] == 'All') ? 'selected' : '' ?>>All</option>
        <option value="Pending" <?= (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
        <option value="Approved" <?= (isset($_GET['status']) && $_GET['status'] == 'Approved') ? 'selected' : '' ?>>Approved</option>
        <option value="Rejected" <?= (isset($_GET['status']) && $_GET['status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
        <option value="Cancel Requested" <?= (isset($_GET['status']) && $_GET['status'] == 'Cancel Requested') ? 'selected' : '' ?>>Cancel Requested</option>
        <option value="Cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>
</form>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>User</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                    <form method="POST" action="update_order_status.php">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="Approved">
                        <input type="submit" value="Approve">
                    </form>
                    <form method="POST" action="update_order_status.php">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="Rejected">
                        <input type="submit" value="Reject">
                    </form>
                <?php } elseif ($row['status'] == 'Cancel Requested') { ?>
                    <form method="POST" action="update_order_status.php">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="Cancelled">
                        <input type="submit" value="Approve Cancellation">
                    </form>
                <?php } else {
                    echo "-";
                } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
