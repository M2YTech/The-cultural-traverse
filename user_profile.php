<?php
session_start();
require 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    exit();
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $userId = $_POST['user_id'];

    // Fetch the user's email
    $sql = "SELECT email FROM users WHERE id='$userId'";
    $result = $conn->query($sql);
    $sql = "SELECT name FROM users WHERE id='$userId'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {  // Check if the query returned a row
        $row = $result->fetch_assoc();
        $userEmail = $row['email'];
        $username= $row['name'];

        // Delete related records from travel_preferences table using the email
        $sql = "DELETE FROM travel_preferences WHERE user_email='$userEmail'";
        $conn->query($sql);
        // Delete related records from trip_request table using the email
        $sql = "DELETE FROM trip_requests WHERE name='$username'";
        $conn->query($sql);

        // Delete related records from past_experiences table using the user ID
        $sql = "DELETE FROM past_experiences WHERE user_id='$userId'";
        $conn->query($sql);

        // Delete the user from the users table
        $sql = "DELETE FROM users WHERE id='$userId'";
        if ($conn->query($sql) === TRUE) {
            $message = "User deleted successfully.";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "danger";
        }
    } else {
        $message = "Error: User not found.";
        $message_type = "danger";
    }

    // Redirect to clear the form submission data and message
    header("Location: user_profile.php?message=$message&message_type=$message_type");
    exit();
}

// Display message if redirected
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $message_type = $_GET['message_type'];
}

// Fetch all users from the users table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profiles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        .container {
            margin-top: 50px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-delete {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#"><img src="./images/logo.png" width="50px" alt="">
        <h4>Admin Panel</h4></a>
        <a href="user_request.php">User Request</a>
        <a href="user_profile.php">Users</a>
        <a href="package_manage.php">Package Management</a>
        <a href="trip_request.php">Trip Request</a>
        <a href="user_custom_request.php">Custom Trip Request</a>
        <a href="user_reviews.php">User Reviews</a>
        <a href="admin_logout.php">Logout</a> <!-- Added a logout option -->
    </div>
    <div class="container">
        <h1 class="text-center">User Profiles</h1>
        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show text-center" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                // Clear URL parameters after displaying the message
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.pathname);
                }
            </script>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td>
                                <form action="user_profile.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
