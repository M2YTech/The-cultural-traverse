<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email']; // Get the user's email from the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destination = $_POST['destination'];
    $date_of_tour = $_POST['date_of_tour'];
    $duration = $_POST['duration'];
    $amount = $_POST['amount'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment']; // New comment field

    $sql = "INSERT INTO past_experiences (user_id, user_email, destination, date_of_tour, duration, amount, rating, comment)
            VALUES ('$user_id', '$user_email', '$destination', '$date_of_tour', '$duration', '$amount', '$rating', '$comment')";
    
    if ($conn->query($sql) === TRUE) {
        // Set a session flag to indicate success
        $_SESSION['success'] = true;
        // Redirect to avoid form resubmission
        header("Location: past_experiences.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Check if success flag is set and unset it
$successMessage = "";
if (isset($_SESSION['success'])) {
    $successMessage = "Experience added successfully.";
    unset($_SESSION['success']);
}

// Handle review deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM past_experiences WHERE id='$delete_id' AND user_id='$user_id'";
    $conn->query($delete_sql);
    header("Location: past_experiences.php");
    exit();
}

// Fetch user's past experiences
$sql = "SELECT * FROM past_experiences WHERE user_id='$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Past Travel Experiences</title>
    <!-- Bootstrap CSS for styling -->
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
        .sidebar a:hover {
            background-color: blue;
        }
        .sidebar .home a:hover {
            background-color: #0f314d;
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
        .container h1{
            color:wheat;
            font-size:larger;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 30px;
            color: lightgray;
            cursor: pointer;
        }
        .rating input:checked ~ label {
            color: gold;
        }
        .rating input:hover ~ label,
        .rating input:hover ~ label ~ label {
            color: gold;
        }
        h3{
            color:black;
            margin-left: 355px;
        }
        @media  screen and (max-width:1200px){
        h3{
            margin-left: 224px;
        }
        }
        .table{
            border: 1px solid black;
        }
        .table th{
            background: #218dca;
            padding-left: 21px;
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
        <h1 class="text-center">Past Travel Experiences</h1>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <form action="past_experiences.php" method="POST" class="mb-5">
            <div class="mb-3">
                <label for="destination" class="form-label">Destination:</label>
                <input type="text" id="destination" name="destination" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="date_of_tour" class="form-label">Date of Tour:</label>
                <input type="date" id="date_of_tour" name="date_of_tour" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration:</label>
                <input type="text" id="duration" name="duration" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount:</label>
                <input type="number" step="0.01" id="amount" name="amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating:</label>
                <div class="rating">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment:</label>
                <textarea id="comment" name="comment" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Experience</button>
        </form>

    </div>
    <h3>Your Past Experiences</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Destination</th>
                    <th>Date of Tour</th>
                    <th>Duration</th>
                    <th>Amount</th>
                    <th>Rating</th>
                    <th>Actions</th> <!-- Added for delete option -->
                </tr>
            </thead>
            <tbody class="data-table">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_of_tour']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td style="color: goldenrod;"><?php echo str_repeat('★', $row['rating']); ?></td> <!-- Display stars based on rating -->
                    <td>
                        <a href="past_experiences.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS for functionality (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
