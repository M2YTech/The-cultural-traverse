<?php
session_start();
require 'config.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch custom trip requests and user information
$sql = "SELECT ctr.id, ctr.start_date, u.name AS user_name 
        FROM custom_trip_requests ctr 
        JOIN users u ON ctr.user_id = u.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Custom Trip Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
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
</style>
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
    <div class="container mt-5">
        <h2>User Custom Trip Requests</h2>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Trip Start Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td>
                                <!-- View request button -->
                                <a href="view_request.php?id=<?php echo $row['id']; ?>" class="btn btn-info">View Request</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No custom trip requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
