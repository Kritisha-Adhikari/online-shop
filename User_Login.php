if ($role === 'admin') {
    header("Location: admin_dashboard.php");
} elseif ($role === 'user') {
    header("Location: user_dashboard.php");
} else {
    echo "Invalid role!";
}
