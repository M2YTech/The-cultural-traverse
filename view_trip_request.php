<?php
session_start();
require 'config.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the trip request ID from the URL
$request_id = $_GET['id'];

// Fetch the detailed trip request data
$sql = "SELECT * FROM custom_trip_requests WHERE id = '$request_id' AND user_id = '{$_SESSION['user_id']}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $trip = $result->fetch_assoc();
    
    // Fetch location names from the 'locations' table
    $location_ids = explode(',', $trip['location_names']);
    $location_names = [];
    
    foreach ($location_ids as $id) {
        $location_sql = "SELECT location_name FROM locations WHERE id = '$id'";
        $location_result = $conn->query($location_sql);
        if ($location_result->num_rows > 0) {
            $location = $location_result->fetch_assoc();
            $location_names[] = $location['location_name'];
        }
    }
    
    // Convert array of location names to a comma-separated string
    $location_names_str = implode(', ', $location_names);

    // Calculate end date based on start date and days spent at each location
    $start_date = $trip['start_date'];
    $days_spent_at_locations = explode(',', $trip['days_per_location']);
    $total_days = array_sum($days_spent_at_locations); // Total days at all locations
    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . ($total_days - 1) . ' days'));
    
} else {
    echo "No such request found.";
    exit();
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
        <h2>Trip Request Details</h2>
        <table class="table table-striped">
            <tr>
                <th>Number of Days per Location:</th>
                <td><?php echo $trip['days_per_location']; ?></td>
            </tr>
            <tr>
                <th>Location(s):</th>
                <td><?php echo $location_names_str; ?></td>
            </tr>
            <tr>
                <th>Start Date:</th>
                <td><?php echo $trip['start_date']; ?></td>
            </tr>
            <tr>
                <th>End Date:</th>
                <td><?php echo $end_date; ?></td> <!-- Display calculated end date -->
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
            <!-- Added Status and Admin Reason -->
            <tr>
                <th>Request Status:</th>
                <td><?php echo $trip['status']; ?></td> <!-- Display the status of the trip request -->
            </tr>
            <?php if (!empty($trip['admin_reason'])): ?> <!-- Only show if the reason exists -->
            <tr>
                <th>Admin's Reason:</th>
                <td><?php echo $trip['admin_reason']; ?></td> <!-- Display the reason provided by the admin -->
            </tr>
            <?php endif; ?>
        </table>
        
        <!-- Update and Back Buttons -->
        <a href="my_custom_trips.php" class="btn btn-secondary">Back to Requests</a>
        <a href="update_trip_request.php?id=<?php echo $trip['id']; ?>" class="btn btn-primary">Update Trip Request</a>
    </div>
</body>
</html>
