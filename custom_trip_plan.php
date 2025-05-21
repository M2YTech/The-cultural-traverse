<?php
session_start();
require 'config.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch locations from the database
$sql = "SELECT id, location_name FROM locations";
$result = $conn->query($sql);

// Handle form submission
$success_message = ""; // To store success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location_ids = $_POST['location']; // Array of selected location IDs
    $start_date = $_POST['start_date'];
    $days_spent = $_POST['days_spent'];
    $budget = $_POST['budget'];
    $accommodation_type = $_POST['accommodation_type'];
    $num_rooms = $_POST['num_rooms'];

    // Convert location array to a comma-separated string to store in the database
    $location_ids_str = implode(',', $location_ids);

    // Insert data into the custom_trip_requests table
    $sql = "INSERT INTO custom_trip_requests (user_id, location_names, start_date, days_per_location, budget, accommodation_type, number_of_rooms)
            VALUES ('{$_SESSION['user_id']}', '$location_ids_str', '$start_date', '$days_spent', '$budget', '$accommodation_type', '$num_rooms')";

    if ($conn->query($sql) === TRUE) {
        // After successful submission, set success message
        $success_message = "<div class='alert alert-success'>Trip request submitted successfully!</div>";
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
    <title>Custom Trip Planning</title>
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
        .container {
            background-color: black;
            border-radius: 10px;
            box-shadow: 13px 14px 102px rgb(10 233 204 / 10%);
            padding: 30px;
            max-width: 400px;
        }
        .container .mb-5{
            color:white;
        }
        .container h1{
            color:wheat;
            font-size:larger;
        }
        label{
            color:white;
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
        <h1 class="text-center">Plan Your Custom Trip</h1>
        
        <!-- Display success message after h2 -->
        <?php if (!empty($success_message)) { echo $success_message; } ?>
        
        <form method="POST" action="custom_trip_plan.php">
            <!-- Location Names -->
            <div class="mb-3">
                <label for="location_names" class="form-label">Select Locations</label>
                <select name="location[]" id="location" multiple class="form-control" required>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['location_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Start Date -->
            <div class="mb-3">
                <label for="start_date" class="form-label">Trip Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>

            <!-- Days to Spend at Each Location (Comma-separated) -->
            <div class="mb-3">
                <label for="days_spent" class="form-label">Days to Spend at Each Location (comma-separated)</label>
                <input type="text" name="days_spent" id="days_spent" class="form-control" placeholder="e.g., 2,3,1" required>
            </div>
            <!-- End Date (Read-only) -->
            <div class="mb-3">
                <label for="end_date" class="form-label">Trip End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" readonly>
            </div>

            <!-- Budget -->
            <div class="mb-3">
                <label for="budget" class="form-label">Budget (in USD)</label>
                <input type="number" name="budget" class="form-control" placeholder="Enter your budget" required>
            </div>

            <!-- Accommodation -->
            <div class="mb-3">
                <label for="accommodation_type" class="form-label">Accommodation Type</label>
                <select name="accommodation_type" class="form-select" required>
                    <option value="Economy">Economy</option>
                    <option value="Standard">Standard</option>
                    <option value="Luxury">Luxury</option>
                </select>
            </div>

            <!-- Number of Rooms -->
            <div class="mb-3">
                <label for="num_rooms" class="form-label">Number of Rooms</label>
                <input type="number" name="num_rooms" class="form-control" placeholder="Enter number of rooms" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>
    <script>
    const startDateInput = document.getElementById('start_date');
    const daysSpentInput = document.getElementById('days_spent');
    const endDateInput = document.getElementById('end_date');

    // Function to calculate and update the end date
    function updateEndDate() {
    const startDateValue = startDateInput.value;
    const daysSpentValue = daysSpentInput.value;

    if (startDateValue && daysSpentValue) {
        const daysArray = daysSpentValue.split(',').map(Number); // Convert comma-separated days into an array of numbers
        const totalDays = daysArray.reduce((acc, days) => acc + days, 0); // Sum the total days
        
        const startDate = new Date(startDateValue);
        startDate.setDate(startDate.getDate() + totalDays - 1); // Subtract 1 to adjust for the first day being included

        const formattedEndDate = startDate.toISOString().split('T')[0]; // Format the date to YYYY-MM-DD
        endDateInput.value = formattedEndDate;
    }
}

    // Event listeners to trigger end date update
    startDateInput.addEventListener('change', updateEndDate);
    daysSpentInput.addEventListener('input', updateEndDate);
</script>
</body>
</html>
