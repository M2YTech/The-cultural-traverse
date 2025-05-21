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

$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_name = $user['name'];

// Fetch user's travel preferences
$sql_preferences = "SELECT * FROM travel_preferences WHERE user_email = '$user_email'"; // replace with actual user email
$result_preferences = $conn->query($sql_preferences);
$preferences = $result_preferences->fetch_assoc();

// Fetch matching travel packages
$sql_packages = "SELECT * FROM travel_packages WHERE  hotel_type = '{$preferences['hotel_type']}'  
                 AND room_type = '{$preferences['room_type']}' 
                 AND meal_plan = '{$preferences['meal_plan']}'";
$result_packages = $conn->query($sql_packages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            margin: 15px;
        }
    </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="recommendations.php">Recomendations</a>
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

<div class="container">
    <h1 class="my-4">Recommended Travel Packages</h1>
    <div class="row">
        <?php
        if ($result_packages->num_rows > 0) {
            while ($package = $result_packages->fetch_assoc()) {
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="<?php echo $package['image']; ?>" class="card-img-top" alt="Package Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $package['location_name']; ?></h5>
                            <p class="card-text">Duration: <?php echo $package['duration']; ?> days</p>
                            <p class="card-text">Price: <?php echo $package['price']; ?> PKR</p>
                            <p class="card-text">Hotel Type: <?php echo $package['hotel_type']; ?></p>
                            <p class="card-text">Room Type: <?php echo $package['room_type']; ?></p>
                            <p class="card-text">Meal Plan: <?php echo $package['meal_plan']; ?></p>
                            <a href="trips.php?highlight_id=<?php echo $package['id']; ?>" class="btn btn-primary">View Trip</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No matching packages found.</p>";
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
