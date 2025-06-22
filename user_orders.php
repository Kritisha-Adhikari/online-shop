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

// Status filter from dropdown
$status_filter = "";
if (isset($_GET['status']) && $_GET['status'] != 'All') {
    $status = $conn->real_escape_string($_GET['status']);
    $status_filter = "AND orders.status = '$status'";
}

$sql = "SELECT orders.id, products.name, orders.quantity, orders.status
        FROM orders
        JOIN products ON orders.product_id = products.id
        WHERE orders.username = '$username' $status_filter";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        h2, p, form { text-align: center; }
        a.cancel-link {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        a.cancel-link:hover {
            text-decoration: underline;
        }
        a.invoice-link {
            color: green;
            text-decoration: none;
            font-weight: bold;
        }
        a.invoice-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>My Orders</h2>

    <form method="GET" action="user_orders.php">
        <label for="status">Filter by Status:</label>
        <select name="status" onchange="this.form.submit()">
            <option value="All" <?php if (!isset($_GET['status']) || $_GET['status'] == 'All') echo 'selected'; ?>>All</option>
            <option value="Approved" <?php if (isset($_GET['status']) && $_GET['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
            <option value="Pending" <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Rejected" <?php if (isset($_GET['status']) && $_GET['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
            <option value="Cancelled" <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
    </form>

    <br>

    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending'): ?>
                            <a class="cancel-link" href="cancel_order.php?order_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</a>
                        <?php elseif ($row['status'] == 'Approved'): ?>
                            <a class="invoice-link" href="invoice.php?order_id=<?php echo $row['id']; ?>" target="_blank">View Invoice</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        <?php else: ?>
            <tr><td colspan="4">No orders found.</td></tr>
        <?php endif; ?>
    </table>

    <p style="text-align:center;"><a href="user_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
