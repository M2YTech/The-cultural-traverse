<?php
session_start();
require 'config.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get the trip request ID from the URL
$request_id = $_GET['id'];

// Fetch the detailed trip request data
$sql = "SELECT ctr.*, u.name AS user_name 
        FROM custom_trip_requests ctr 
        JOIN users u ON ctr.user_id = u.id 
        WHERE ctr.id = '$request_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $trip = $result->fetch_assoc();
} else {
    echo "No such request found.";
    exit();
}

// Handle form submission for acceptance/rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $decision = $_POST['decision'];
    $reason = $_POST['reason'];
    
    // Update the trip request status in the database
    if ($decision === 'accept') {
        $status = "Accepted";
    } elseif ($decision === 'reject') {
        $status = "Rejected";
    }

    $sql = "UPDATE custom_trip_requests 
            SET status = '$status', admin_reason = '$reason' 
            WHERE id = '$request_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Trip request has been $status successfully!</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Trip Request</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Trip Request Details for <?php echo $trip['user_name']; ?></h2>
        <table class="table table-striped">
            <tr>
                <th>Location(s):</th>
                <td><?php echo $trip['location_names']; ?></td>
            </tr>
            <tr>
                <th>Start Date:</th>
                <td><?php echo $trip['start_date']; ?></td>
            </tr>
            <tr>
                <th>Days Spent at Each Location:</th>
                <td><?php echo $trip['days_per_location']; ?></td>
            </tr>
            <tr>
                <th>Budget:</th>
                <td><?php echo $trip['budget']; ?></td>
            </tr>
            <tr>
                <th>Accommodation Type:</th>
                <td><?php echo $trip['accommodation_type']; ?></td>
            </tr>
            <tr>
                <th>Number of Rooms:</th>
                <td><?php echo $trip['number_of_rooms']; ?></td>
            </tr>
        </table>

        <!-- Admin Decision Form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="decision" class="form-label">Decision</label>
                <select name="decision" class="form-select" required>
                    <option value="accept">Accept</option>
                    <option value="reject">Reject</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Decision</label>
                <textarea name="reason" class="form-control" rows="4" required placeholder="Provide a reason for acceptance or rejection"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Decision</button>
        </form>

        <a href="user_custom_request.php" class="btn btn-secondary mt-3">Back to Requests</a>
    </div>
</body>
</html>
