<?php
session_start();

// Clear session variables
session_unset();
session_destroy();

// Clear cookies
if (isset($_COOKIE['admin_id'])) {
    setcookie('admin_id', '', time() - 3600, '/');
}
if (isset($_COOKIE['admin_email'])) {
    setcookie('admin_email', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: admin_login.php");
exit();
?>
