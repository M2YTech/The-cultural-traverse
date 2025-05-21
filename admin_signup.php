<?php
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists
    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Insert the new admin into the database
        $sql = "INSERT INTO admins (email, phone_number, password) VALUES ('$email', '$phone_number', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "Admin registered successfully.";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "danger";
        }
    } else {
        $message = "Email already exists.";
        $message_type = "warning";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="./css/style-1.css">
</head>
<body>
    <div class="container">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> text-center" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form action="admin_signup.php" method="POST">
                <h1>Admin Signup</h1>
                <div class="input-box">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter your phone number" required>
                </div>
                <div class="input-box">
                    <input type="password" id="myInput" name="password" class="form-control" placeholder="Enter your password" required>
                    <span class="eye" onclick="myFunction()">
                        <i id="hide1" class="fa fa-eye-slash"></i>
                        <i id="hide2" class="fa fa-eye"></i>
                    </span>
                </div>
                <button type="submit" class="btn">Signup</button>
                <div class="register-link">
                    <p>Already have an account? <a href="admin_login.php">Signin here!</a></p>
                </div>
            </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/icon.js"></script>
</body>
</html>
