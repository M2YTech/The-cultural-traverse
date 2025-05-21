<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_GET['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $sql = "UPDATE users SET password='$new_password' WHERE email='$email'";
    if ($conn->query($sql) === TRUE) {
        echo "Password updated successfully.";
        header("Location: login.php");
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css/style-1.css">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
    <form action="reset_password.php?email=<?php echo $_GET['email']; ?>" method="POST">
        <div class="input-box">
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn">Reset Password</button>
    </form>
    </div>
</body>
</html>
