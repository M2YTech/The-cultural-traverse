<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $sql = "UPDATE users SET name='$name', email='$email', phone_number='$phone_number' WHERE id='$user_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Profile updated successfully.";
    } else {
        $message = "Error updating profile: " . $conn->error;
    }
}

// Fetch current user data
$sql = "SELECT name, email, phone_number FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Personal Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f8f8;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background-color: #0f314d;
            height: auto;
            padding-top: 20px;
            padding-left: 5px
        }
        .sidebar img {
            display: inline;
            width: 30px;
            position: absolute;
            margin-top:7px;
        }
        .sidebar a h4 {
            font-size: 22px;
            margin-left: 46px;
            color: white;
            margin-bottom: 12px;
            margin-top: 11px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 17px;
        }
        .sidebar a:hover {
            background-color: blue;
        }
        .sidebar .home a:hover {
            background-color: #0f314d;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .container{
            background-color: black;
            border-radius: 10px;
            box-shadow: 13px 14px 102px rgb(10 233 204 / 10%);
            padding: 30px;
            max-width: 400px;
            margin-bottom: 45px;
        }
        .container h1{
            color:wheat;
            font-size:larger;
        }
        .mb-3 label{
            color:white;
        }
        .btn-primary {
            width: 100%;
        }
        .form-text {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="home">
        <a href="landing_page.php" ><img src="./images/logo.png" width="50px" alt="">
        <h4>Profile Management</h4></a>
        </div>
        
        <a href="update_personal_info.php">Update Personal Information</a>
        <a href="travel_preferences.php">Travel Preferences</a>
        <a href="past_experiences.php">Past Travel Experiences</a>
        <a href="my_trips.php">My Trips</a>
        <a href="custom_trip_plan.php">Design custom trips</a>
        <a href="my_custom_trips.php">My custom trips</a>
    </div>
    <div class="content">
        <div class="container mt-5">
        <h1 class="text-center mb-4">Update Personal Information</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="update_personal_info.php" class="mt-4" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Information</button>
            <div class="form-text">
                <a href="landing_page.php">Back to Profile</a>
            </div>
        </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
