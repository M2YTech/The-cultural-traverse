<?php
session_start();
require 'config.php'; // Your database configuration file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = ''; // Feedback message after form submission

// Handle package form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location_name = mysqli_real_escape_string($conn,$_POST['location_name']);
    $travel_date = mysqli_real_escape_string($conn,$_POST['travel_date']);
    $duration = mysqli_real_escape_string($conn,$_POST['duration']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $hotel_type = mysqli_real_escape_string($conn, $_POST['hotel_type']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $meal_plan = mysqli_real_escape_string($conn, $_POST['meal_plan']);

    // Check for duplicate travel date
    $check_date_query = "SELECT * FROM travel_packages WHERE travel_date = '$travel_date'";
    $result = $conn->query($check_date_query);

    if($result->num_rows > 0 ){
        // Trip on the same date found
        $message = "Error: A trip is already scheduled for this date. Please choose another date.";
    } else {
        // Handle image upload
        if ($_FILES['image']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            $target_file = '';
        }

        // Insert package into database with hotel_type, room_type, meal_plan
        $sql = "INSERT INTO travel_packages (image, location_name, travel_date, duration, price, description, hotel_type, room_type, meal_plan) 
                VALUES ('$target_file', '$location_name', '$travel_date', '$duration', '$price', '$description', '$hotel_type', '$room_type', '$meal_plan')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Package added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Handle package deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Fetch the image path before deleting the package
    $image_query = "SELECT image FROM travel_packages WHERE id='$delete_id'";
    $image_result = $conn->query($image_query);
    
    if ($image_result->num_rows > 0) {
        $row = $image_result->fetch_assoc();
        $image_path = $row['image'];
        
        // Check if the file exists and delete it
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Proceed to delete the package from the database
    $sql = "DELETE FROM travel_packages WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Package deleted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all packages from database
$sql = "SELECT * FROM travel_packages";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Travel Packages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Add your CSS styles here */
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
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .container{
            background-color: black;
            border-radius: 10px;
            box-shadow: 13px 14px 102px rgb(10 233 204 / 10%);
            padding: 30px;
            max-width: 400px;
        }
        .container .mb-5{
            color:white;
        }
        h2{
            color:black;
            margin-left: 355px;
        }
        @media screen and (max-width:1200px){
            h2{
                margin-left: 224px;
            }
        }
        .table th{
            background: #218dca;
            padding-left: 21px;
        }
    </style>
</head>
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
    <div class="content">
        <h1 class="mb-4 text-center">Manage Travel Packages</h1>
        <div class="container mt-5">

        <!-- Feedback Message -->
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Form for adding new packages -->
        <form action="package_manage.php" method="POST" enctype="multipart/form-data" class="mb-5">
            <div class="mb-3">
                <label for="image" class="form-label">Select Location Image:</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="location_name" class="form-label">Location Name:</label>
                <input type="text" name="location_name" id="location_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="travel_date" class="form-label">Travel Date:</label>
                <input type="date" name="travel_date" id="travel_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (in days):</label>
                <input type="number" name="duration" id="duration" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="hotel_type" class="form-label">Type of Hotel:</label>
                <select name="hotel_type" id="hotel_type" class="form-control" required>
                    <option value="Basic">Basic</option>
                    <option value="Standard">Standard</option>
                    <option value="Luxury">Luxury</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="room_type" class="form-label">Room Type:</label>
                <select name="room_type" id="room_type" class="form-control" required>
                    <option value="Single Room">Single Room</option>
                    <option value="Double Room">Double Room</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="meal_plan" class="form-label">Meal Plan:</label>
                <select name="meal_plan" id="meal_plan" class="form-control" required>
                    <option value="Room Only">Room Only</option>
                    <option value="Bed and Breakfast">Bed and Breakfast</option>
                    <option value="All Inclusive">All Inclusive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Package</button>
        </form>
        </div>
          <!-- Display the table of packages -->
          <h2>Existing Packages</h2>
          <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Location Name</th>
                    <th>Travel Date</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Hotel Type</th>
                    <th>Room Type</th>
                    <th>Meal Plan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['location_name']; ?></td>
                        <td><?php echo $row['travel_date']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['hotel_type']; ?></td>
                        <td><?php echo $row['room_type']; ?></td>
                        <td><?php echo $row['meal_plan']; ?></td>
                        <td>
                            <a href="package_manage.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JavaScript for responsive tables -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

