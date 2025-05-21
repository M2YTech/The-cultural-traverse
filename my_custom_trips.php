<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete request
if (isset($_POST['delete_request'])) {
    $request_id = $_POST['request_id'];
    $delete_sql = "DELETE FROM custom_trip_requests WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $request_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success'>Trip request deleted successfully.</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Error deleting the trip request.</div>";
    }

    // Redirect to the same page to avoid form resubmission
    header("Location: my_custom_trips.php");
    exit();
}

// Fetch user's custom trip requests along with location names
$sql = "SELECT ctr.id, GROUP_CONCAT(l.location_name SEPARATOR ', ') AS location_names, ctr.start_date, ctr.budget 
        FROM custom_trip_requests ctr
        JOIN locations l ON FIND_IN_SET(l.id, ctr.location_names) > 0
        WHERE ctr.user_id = ?
        GROUP BY ctr.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Custom Trips</title>
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

<div class="container mt-5">
    <h2>My Custom Trips</h2>

    <!-- Display alert message after the h2 tag -->
    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Clear message after displaying
    }
    ?>

    <!-- Display trips -->
    <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Location Name(s)</th>
                <th>Date</th>
                <th>Budget</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['location_names']); ?></td>
                <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                <td><?php echo htmlspecialchars($row['budget']); ?></td>
                <td>
                    <!-- View Complete Request -->
                    <a href="view_trip_request.php?id=<?php echo $row['id']; ?>" class="btn btn-info">View</a>

                    <!-- Delete Request -->
                    <form method="POST" action="my_custom_trips.php" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_request" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this trip request?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info">You have no custom trip requests.</div>
    <?php endif; ?>
</div>
</body>
</html>
