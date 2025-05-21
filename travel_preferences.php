<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
$user_email = $_SESSION['user_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $duration = implode(", ", $_POST['duration']);
    $hotel_type = $_POST['hotel_type'];
    $room_type = $_POST['room_type'];
    $meal_plan = $_POST['meal_plan'];
    $budget_range = implode(", ", $_POST['budget_range']);

    // Insert or update the travel preferences in the database
    $sql = "INSERT INTO travel_preferences (user_email, duration, hotel_type, room_type, meal_plan, budget_range)
            VALUES ('$user_email', '$duration', '$hotel_type', '$room_type', '$meal_plan', '$budget_range')
            ON DUPLICATE KEY UPDATE 
            duration=VALUES(duration), hotel_type=VALUES(hotel_type), room_type=VALUES(room_type), meal_plan=VALUES(meal_plan), budget_range=VALUES(budget_range)";
    
    if ($conn->query($sql) === TRUE) {
        // Set a session message for successful update
        $_SESSION['success_message'] = "Preferences updated successfully!";
        // Redirect to prevent form resubmission and show the alert
        header("Location: travel_preferences.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch the current travel preferences
$sql = "SELECT * FROM travel_preferences WHERE user_email='$user_email'";
$result = $conn->query($sql);
$preferences = $result->fetch_assoc() ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Preferences</title>
    <!-- Bootstrap CSS -->
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
        .content {
            flex-grow: 1;
            padding: 20px;
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
        .mt-4{
            color:white;
            font-size:medium;
        } 
        .second{
            margin-left: 355px;
        }
        @media  screen and (max-width:1200px){
        .second{
            margin-left: 224px;
        }
        }
        .form-check label{
            color:white;
        }
        h1, h3, h2 {
            font-family: 'Arial', sans-serif;
            color: black;
        }
        .form-check-label {
            margin-left: 10px;
            font-weight: 500;
        }
        .table {
            margin-top: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .table th{
            background: #218dca;
            padding-left: 21px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control {
            border-radius: 5px;
        }
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
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
        <a href="custom_trip_plan.php">Desing custom trips</a>
        <a href="my_custom_trips.php">My custom trips</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Travel Preferences</h1>
            
            <!-- Success Alert -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php 
                // Unset the message after displaying it
                unset($_SESSION['success_message']); 
                ?>
            <?php endif; ?>

            <form action="travel_preferences.php" method="POST">
                <!-- Form fields for preferences (unchanged) -->

                <!-- Duration -->
                <h2 class="mt-4 ">Duration:</h2>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="duration[]" value="1-2 days" id="1-2days" <?php if (strpos($preferences['duration'] ?? '', '1-2 days') !== false) echo 'checked'; ?>>
                    <label for="1-2days" class="form-check-label">1-2 days</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="duration[]" value="2-3 days" id="2-3days" <?php if (strpos($preferences['duration'] ?? '', '2-3 days') !== false) echo 'checked'; ?>>
                    <label for="2-3days" class="form-check-label">2-3 days</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="duration[]" value="7 days" id="7days" <?php if (strpos($preferences['duration'] ?? '', '7 days') !== false) echo 'checked'; ?>>
                    <label for="7days" class="form-check-label">7 days</label>
                </div>

                <!-- Hotel Type -->
                <h2 class="mt-4">Hotel Type:</h2>
                <select name="hotel_type" class="form-control">
                    <option value="basic" <?php if (($preferences['hotel_type'] ?? '') == 'basic') echo 'selected'; ?>>Basic</option>
                    <option value="standard" <?php if (($preferences['hotel_type'] ?? '') == 'standard') echo 'selected'; ?>>Standard</option>
                    <option value="luxury" <?php if (($preferences['hotel_type'] ?? '') == 'luxury') echo 'selected'; ?>>Luxury</option>
                </select>

                <!-- Room Type -->
                <h2 class="mt-4">Room Type:</h2>
                <select name="room_type" class="form-control">
                    <option value="single room" <?php if (($preferences['room_type'] ?? '') == 'single room') echo 'selected'; ?>>Single Room</option>
                    <option value="double room" <?php if (($preferences['room_type'] ?? '') == 'double room') echo 'selected'; ?>>Double Room</option>
                    <option value="suite" <?php if (($preferences['room_type'] ?? '') == 'suite') echo 'selected'; ?>>Suite</option>
                </select>

                <!-- Meal Plan -->
                <h2 class="mt-4">Meal Plan:</h2>
                <select name="meal_plan" class="form-control">
                    <option value="room only" <?php if (($preferences['meal_plan'] ?? '') == 'room only') echo 'selected'; ?>>Room Only</option>
                    <option value="bed and breakfast" <?php if (($preferences['meal_plan'] ?? '') == 'bed and breakfast') echo 'selected'; ?>>Bed and Breakfast</option>
                    <option value="all inclusive" <?php if (($preferences['meal_plan'] ?? '') == 'all inclusive') echo 'selected'; ?>>All Inclusive</option>
                </select>

                <!-- Budget Range -->
                <h2 class="mt-4">Budget Range:</h2>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="budget_range[]" value="5000-10000" id="5000-10000" <?php if (strpos($preferences['budget_range'] ?? '', '5000-10000') !== false) echo 'checked'; ?>>
                    <label for="5000-10000" class="form-check-label">5000-10000</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="budget_range[]" value="10000-15000" id="10000-15000" <?php if (strpos($preferences['budget_range'] ?? '', '10000-15000') !== false) echo 'checked'; ?>>
                    <label for="10000-15000" class="form-check-label">10000-15000</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="budget_range[]" value="15000plus" id="15000plus" <?php if (strpos($preferences['budget_range'] ?? '', '15000plus') !== false) echo 'checked'; ?>>
                    <label for="15000plus" class="form-check-label">15000+</label>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Update Preferences</button>
            </form>
        </div>

        <!-- Display Selected Preferences in a Table -->
        <?php if ($preferences): ?>
            <h3 class="mt-5 second">Your Selected Preferences:</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Duration</th>
                        <th>Hotel Type</th>
                        <th>Room Type</th>
                        <th>Meal Plan</th>
                        <th>Budget Range</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $preferences['duration']; ?></td>
                        <td><?php echo $preferences['hotel_type']; ?></td>
                        <td><?php echo $preferences['room_type']; ?></td>
                        <td><?php echo $preferences['meal_plan']; ?></td>
                        <td><?php echo $preferences['budget_range']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS (for dismissing the alert) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
