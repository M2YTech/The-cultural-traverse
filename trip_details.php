<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_name = $user['name'];
$user_email = $user['email'];

// Fetch the package details based on the selected package_id
if (isset($_GET['package_id'])) {
    $package_id = $_GET['package_id'];
    $sql = "SELECT * FROM travel_packages WHERE id = $package_id";
    $result = $conn->query($sql);
    $package = $result->fetch_assoc();
} else {
    echo "Invalid package selected.";
    exit;
}

// Check if the user has already booked this trip
$sql_check_booking = "SELECT * FROM trip_requests WHERE package_id = '$package_id' AND user_email = '$user_email'";
$result_check_booking = $conn->query($sql_check_booking);
$is_booked = $result_check_booking->num_rows > 0;

// Handle form submission when user books a trip
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$is_booked) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_phone = mysqli_real_escape_string($conn, $_POST['user_phone']);
    $package_id = $_POST['package_id'];

    // Insert user request into the database
    $sql = "INSERT INTO trip_requests (package_id, user_name, user_email, user_phone) 
            VALUES ('$package_id', '$user_name', '$user_email', '$user_phone')";
    if ($conn->query($sql) === TRUE) {
        $message = "Your request has been submitted successfully!";
        $is_booked = true; // Set the booking status to true after submission
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/landing.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <img src="./images/logo.png" alt="" width="50px">
        <a class="navbar-brand" href="#">CulturalTraverse</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="landing_page.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="trips.php">Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">Reviews</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link">Welcome, <?php echo $user_name; ?></span>
                </li>
                <li class="nav-item">
                    <a href="past_experiences.php" class="btn btn-primary">User Panel</a>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Trip Details</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-info text-center"><?php echo $message; ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php endif; ?>

        <!-- Display Trip Details -->
        <div class="card mb-4">
            <img src="<?php echo $package['image']; ?>" class="card-img-top" alt="Trip Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo ucwords($package['location_name']); ?></h5>
                <p class="card-text"><?php echo $package['description']; ?></p>
                <p><strong>Price:</strong> $<?php echo $package['price']; ?></p>
                <p><strong>Duration:</strong> <?php echo $package['duration']; ?> days</p>
                <p><strong>Hotel Type:</strong> <?php echo $package['hotel_type']; ?></p>
                <p><strong>Room Type:</strong> <?php echo $package['room_type']; ?></p>
                <p><strong>Meal plan:</strong> <?php echo $package['meal_plan']; ?></p>
            </div>
        </div>

        <!-- Booking Form or "Booked" Button -->
        <?php if ($is_booked): ?>
            <button class="btn btn-success btn-block" disabled>Booked</button>
        <?php else: ?>
            <h3>Book This Trip</h3>
            <form action="trip_details.php?package_id=<?php echo $package_id; ?>" method="POST">
                <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
                <div class="mb-3">
                    <label for="user_name" class="form-label">Your Name:</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $user_name; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="user_email" class="form-label">Your Email:</label>
                    <input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo $user_email; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="user_phone" class="form-label">Your Phone:</label>
                    <input type="text" name="user_phone" id="user_phone" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
