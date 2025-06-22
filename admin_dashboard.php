<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "online-shop");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Summary Data निकाल्ने
$counts = [];
$statuses = ['Total' => '', 'Approved' => 'Approved', 'Pending' => 'Pending', 'Rejected' => 'Rejected', 'Cancelled' => 'Cancelled'];

foreach ($statuses as $label => $status) {
    $sql = $status === '' ? "SELECT COUNT(*) FROM orders" : "SELECT COUNT(*) FROM orders WHERE status = '$status'";
    $res = $conn->query($sql);
    $row = $res->fetch_row();
    $counts[$label] = $row[0];
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f3f3f3;
        }
        h2 {
            color: #333;
        }
        .link-box {
            margin-top: 20px;
        }
        a.button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.button:hover {
            background-color: #0056b3;
        }

        table {
            width: 50%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>This is your admin dashboard.</p>

    <!-- 🔽 Order Summary Table -->
    <h3 style="text-align:center;">🧾 Order Summary</h3>
    <table>
        <tr>
            <th>Status</th>
            <th>Count</th>
        </tr>
        <?php foreach ($counts as $label => $count): ?>
        <tr>
            <td><?= $label ?></td>
            <td><?= $count ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="link-box" style="text-align: center;">
        <a href="view_orders_admin.php" class="button">Manage Orders</a>
        <a href="logout.php" class="button" style="background-color: red;">Logout</a>
    </div>

</body>
</html>
