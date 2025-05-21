<?php
session_start();
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: user_request.php");
            exit();
        } else {
            $message = "Invalid password.";
            $message_type = "danger";
        }
    } else {
        $message = "No admin found.";
        $message_type = "danger";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
            <form action="admin_login.php" method="POST">
                <h1>Admin Login</h1>
                <div class="input-box">
                    <input type="email" id="email" name="email" class="form-control" placeholder="email" required>
                </div>
                <div class="input-box">
                    <input type="password" id="myInput" name="password" class="form-control" placeholder="password" required>
                    <span  class="eye" onclick="myFunction()">
                        <i id="hide1" class="fa fa-eye-slash"></i>
                        <i id="hide2" class="fa fa-eye"></i>
                    </span>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="register-link">
                    <p>Don't have an account? <a href="admin_signup.php">Register here!</a></p>
                </div>
            </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/icon.js"></script>
</body>
</html>
