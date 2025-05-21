<?php
session_start();

// Clear session variables
session_unset();
session_destroy();

// Clear cookies
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/');
}
if (isset($_COOKIE['user_email'])) {
    setcookie('user_email', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: login.php");
exit();
?>
