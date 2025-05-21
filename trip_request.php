<?php
session_start();
require 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}



// Handle accept, reject and delete actions
if (isset($_GET['action'])) {
    $request_id = $_GET['request_id'];
    $action = $_GET['action'];

    if ($action == 'accept') {
        $sql = "UPDATE trip_requests SET status = 'Accepted' WHERE id = $request_id";
    } elseif ($action == 'reject') {
        $sql = "UPDATE trip_requests SET status = 'Rejected' WHERE id = $request_id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM trip_requests WHERE id = $request_id";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Request has been updated.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all trip requests
$sql = "SELECT trip_requests.*, travel_packages.location_name, travel_packages.travel_date AS tour_date 
        FROM trip_requests 
        JOIN travel_packages ON trip_requests.package_id = travel_packages.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Requests</title>
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
        <a href="admin_logout.php">Logout</a>
    </div>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Manage Trip Requests</h1>

        <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Table to display trip requests -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>User Phone</th>
                    <th>Trip Location</th>
                    <th>Trip Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['user_email']; ?></td>
                        <td><?php echo $row['user_phone']; ?></td>
                        <td><?php echo $row['location_name']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($row['tour_date'])); ?></td>
                        <td><?php echo $row['status'] ?: 'Pending'; ?></td>
                        <td>
                            <a href="trip_request.php?action=accept&request_id=<?php echo $row['id']; ?>" class="btn btn-success">Accept</a>
                            <a href="trip_request.php?action=reject&request_id=<?php echo $row['id']; ?>" class="btn btn-danger">Reject</a>
                            <a href="trip_request.php?action=delete&request_id=<?php echo $row['id']; ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to delete this request?')">Remove</a> <!-- Remove Button -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
