<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the trip request ID from the URL
$request_id = $_GET['id'];

// Fetch the trip request details
$sql = "SELECT * FROM custom_trip_requests WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $request_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$trip = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $number_of_days = $_POST['number_of_days'];
    $location_names = $_POST['location_names'];
    $start_date = $_POST['start_date'];
    $days_per_location = $_POST['days_per_location'];
    $budget = $_POST['budget'];
    $accommodation_type = $_POST['accommodation_type'];
    $number_of_rooms = $_POST['number_of_rooms'];

    // Update the trip request in the database
    $update_sql = "UPDATE custom_trip_requests SET number_of_days = ?, location_names = ?, start_date = ?, days_per_location = ?, budget = ?, accommodation_type = ?, number_of_rooms = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("isssdsiii", $number_of_days, $location_names, $start_date, $days_per_location, $budget, $accommodation_type, $number_of_rooms, $request_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Trip request updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating the trip request.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Trip Request</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Trip Request</h2>

        <!-- Update Form -->
        <form method="POST">
            <div class="mb-3">
                <label for="number_of_days" class="form-label">Number of Days</label>
                <input type="number" class="form-control" name="number_of_days" id="number_of_days" value="<?php echo $trip['number_of_days']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="location_names" class="form-label">Location Name(s)</label>
                <input type="text" class="form-control" name="location_names" id="location_names" value="<?php echo $trip['location_names']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date" id="start_date" value="<?php echo $trip['start_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="days_per_location" class="form-label">Days Spent at Each Location</label>
                <input type="text" class="form-control" name="days_per_location" id="days_per_location" value="<?php echo $trip['days_per_location']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="budget" class="form-label">Budget</label>
                <input type="text" class="form-control" name="budget" id="budget" value="<?php echo $trip['budget']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="accommodation_type" class="form-label">Accommodation Type</label>
                <input type="text" class="form-control" name="accommodation_type" id="accommodation_type" value="<?php echo $trip['accommodation_type']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="number_of_rooms" class="form-label">Number of Rooms</label>
                <input type="number" class="form-control" name="number_of_rooms" id="number_of_rooms" value="<?php echo $trip['number_of_rooms']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Trip</button>
            <a href="view_trip_request.php?id=<?php echo $trip['id']; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
