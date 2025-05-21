<?php
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the email already exists
    $check_email_sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        // Email already exists
        $message = "This email is already registered. Please use a different email.";
        $message_type = "warning";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (name, email, phone_number, password) VALUES ('$name', '$email', '$phone_number', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "Signup request sent to admin for approval.";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "danger";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style-1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Signup</title>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> text-center alert-dismissible fade show"" role="alert">
                <span class="message"><?php echo $message; ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="signup.php" method="POST">
            <h1>Sign Up</h1>
            <div class="input-box">
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-box">
                <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
            </div>
            <div class="input-box">
                <input type="password" id="myInput" name="password" placeholder="Enter your password" required>
                <span class="eye" onclick="myFunction()">
                    <i id="hide1" class="fa fa-eye-slash"></i>
                    <i id="hide2" class="fa fa-eye"></i>
                </span>
            </div>
            <button class="btn" type="submit">Signup</button>
            <div class="register-link">
                <p>Already have an account? <a href="login.php">Signin here!</a></p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/icon.js"></script>
</body>
</html>
