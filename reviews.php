<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_name = $user['name'];


// Fetch all reviews
$sql = "SELECT * FROM past_experiences ORDER BY date_of_tour DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Reviews</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/landing.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <!-- Other nav items -->
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
            <!-- User info on the right end -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link">Welcome, <?php echo $user_name; ?></span>
                </li>
                <li class="nav-item">
                    <a href="past_experiences.php" class="btn btn-primary">User Panel</a>
                    <a href="logout.php" class="btn btn-primary">Logout</a> <!-- Update the link here -->
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>User Reviews</h1>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['destination']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($row['date_of_tour']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($row['comment']); ?></p>
                        <p><?php echo str_repeat('★', $row['rating']); ?></p> <!-- Display stars -->
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <section class="footer">
    <div class="footer-box">
      <h2>Cultural Traverse</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum, in!</p>
      <div class="social">
        <a href="#"><i class='bx bxl-facebook'></i></a>
        <a href="#"><i class='bx bxl-twitter'></i></a>
        <a href="#"><i class='bx bxl-instagram'></i></a>
        <a href="#"><i class='bx bxl-tiktok'></i></a>
      </div>
    </div>
    <div class="footer-box">
      <h3>Support</h3>
      <li><a href="#"></a>Product</li>
      <li><a href="#"></a>Help & Support</li>
      <li><a href="#"></a>Return Policy</li>
      <li><a href="#"></a>Term of user</li>
      <li><a href="#"></a>Product</li>
    </div>
    <div class="footer-box">
      <h3>View Guides</h3>
      <li><a href="#"></a>Features</li>
      <li><a href="#"></a>Careers</li>
      <li><a href="#"></a>Blog Posts</li>
      <li><a href="#"></a>Our Branches</li>
    </div>
    <div class="footer-box">
      <h3>Contact</h3>
      <div class="contact">
        <span><i class='bx bx-map'></i>250 johar town lahore pakistan</span>
        <span><i class='bx bx-phone'></i>+1 222 444 222</span>
        <span><i class='bx bx-envelop'></i>culturaltraverse@gmail.com</span>
      </div>
    </div>
  </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>
