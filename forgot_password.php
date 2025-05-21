<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $sql = "SELECT * FROM users WHERE email='$email' AND phone_number='$phone_number'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        header("Location: reset_password.php?email=$email");
        exit();
    } else {
        echo "No user found with provided details.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css/style-1.css">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
    <form action="forgot_password.php" method="POST">
        <div class="input-box">
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-box">
            <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
        </div>
        <button type="submit" class="btn">Verify</button>
    </form>
    </div>
</body>
</html>
