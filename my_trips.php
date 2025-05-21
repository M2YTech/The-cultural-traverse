<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql_user = "SELECT email FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();
$user_email = $user['email'];

// Handle trip request deletion
if (isset($_POST['delete_trip'])) {
    $request_id = $_POST['request_id'];
    
    // Delete the trip request from the database
    $sql_delete = "DELETE FROM trip_requests WHERE id = '$request_id' AND user_email = '$user_email'";
    if ($conn->query($sql_delete) === TRUE) {
        $message = "Your trip request has been cancelled.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch the user's trip requests and corresponding package details
$sql = "SELECT trip_requests.*, travel_packages.location_name, travel_packages.travel_date, travel_packages.duration 
        FROM trip_requests 
        JOIN travel_packages ON trip_requests.package_id = travel_packages.id 
        WHERE trip_requests.user_email = '$user_email'";
        
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Trips</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f8f8;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 344px;
            background-color: #0f314d;
            height: auto;
            padding-top: 20px;
            padding-left: 5px;
        }
        .sidebar img {
            display: inline;
            width: 30px;
            position: absolute;
            margin-top: 7px;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="home">
            <a href="landing_page.php"><img src="./images/logo.png" width="50px" alt="">
            <h4>Profile Management</h4></a>
        </div>
        <a href="update_personal_info.php">Update Personal Information</a>
        <a href="travel_preferences.php">Travel Preferences</a>
        <a href="past_experiences.php">Past Travel Experiences</a>
        <a href="my_trips.php">My Trips</a>
        <a href="custom_trip_plan.php">Design custom trips</a>
        <a href="my_custom_trips.php">My custom trips</a>
    </div>
    
    <div class="container mt-5">
        <h1 class="text-center mb-4">My Trips</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-info text-center"><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
            
        <?php endif; ?>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Travel Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo ucwords($row['location_name']); ?></td>
                            <td><?php echo $row['travel_date']; ?></td>
                            <td><?php echo $row['duration']; ?> Days</td>
                            <td>
                                <?php 
                                    if ($row['status'] == 'Accepted') {
                                        echo "<span class='badge bg-success'>Accepted</span>";
                                    } elseif ($row['status'] == 'Rejected') {
                                        echo "<span class='badge bg-danger'>Rejected</span>";
                                    } else {
                                        echo "<span class='badge bg-warning'>Pending</span>";
                                    }
                                ?>
                            </td>
                            <td>
                                <form action="my_trips.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this trip request?');">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_trip" class="btn btn-danger">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">You have not requested any trips yet.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
