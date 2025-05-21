<?php
session_start();
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND is_approved=1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Store user ID and email in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];

            // Check if the "Remember Me" checkbox is checked
            if (isset($_POST['remember_me'])) {
                // Set cookies that expire in 30 days
                setcookie('user_id', $row['id'], time() + (86400 * 30), "/");
                setcookie('user_email', $row['email'], time() + (86400 * 30), "/");
            }

            header("Location: landing_page.php");
            exit();
        } else {
            $message = "Invalid password.";
            $message_type = "danger";
        }
    } else {
        $message = "No user found or account not approved.";
        $message_type = "danger";
    }
    $conn->close();
}

// Check if "Remember Me" cookies are set and log the user in automatically
if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_email'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['user_email'] = $_COOKIE['user_email'];

    header("Location: landing_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style-1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> text-center" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="email" id="email" name="email" placeholder="email" required>
                </div>
                <div class="input-box">
                    <input type="password" id="myInput" name="password" placeholder="password " required>
                    <span class="eye" onclick="myFunction()">
                        <i id="hide1" class="fa fa-eye-slash"></i>
                        <i id="hide2" class="fa fa-eye"></i>
                    </span>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox" id="remember_me" name="remember_me" /> Remember me</label>
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="register-link">
                    <p>Don't have an account? <a href="signup.php">Register here!</a></p>
                </div>
            </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/icon.js"></script>
</body>
</html>
