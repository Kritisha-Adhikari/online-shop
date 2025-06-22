<?php
session_start();
$username = $_SESSION['username'] ?? "Guest";
$logged_in = !empty($_SESSION['username']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Shop</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #007bff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 30px;
        }
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        .navbar .nav-links {
            display: flex;
            gap: 20px;
        }
        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar .nav-links a:hover {
            text-decoration: underline;
        }
        .navbar .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar .user-info a {
            color: white;
            text-decoration: none;
            background-color: red;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .navbar .user-info a:hover {
            background-color: darkred;
        }
        .content {
            flex: 1;
            padding: 100px 20px;
            text-align: center;
        }
        .footer {
            background-color: #222;
            color: #eee;
            text-align: center;
            padding: 15px 20px;
            font-size: 14px;
            margin-top: auto;
        }
        .footer a {
            display: inline-block;
            margin: 0 10px;
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .footer a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">🛍️ MyShop</div>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="help.php">Help</a>
    </div>
    <div class="user-info">
        <?php if ($logged_in): ?>
            <span><?= htmlspecialchars($username) ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.html">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- content div खाली राखियो, home.php मा content राख्ने -->

<div class="footer">
    &copy; <?= date('Y') ?> MyShop. All rights reserved.
    <a href="privacy.php">Privacy Policy</a>
    <a href="terms.php">Terms of Service</a>
</div>

</body>
</html>
