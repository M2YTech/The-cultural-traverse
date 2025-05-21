<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the user's name from the database
require 'config.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_name = $user['name'];

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="./css/landing.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
      <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="./images/1.jpg"  height="650px" width="100%" class="d-block" alt="...">
      </div>
      <div class="carousel-item">
        <img src="./images/2.jpg" height="650px" width="100%" class="d-block" alt="...">
      </div>
      <div class="carousel-item">
        <img src="./images/3.jpg" height="650px" width="100%" class="d-block" alt="...">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-target="#carouselExampleCaptions" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-target="#carouselExampleCaptions" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </button>
  </div>
  <section class="about" id="about">
    <div class="about-img">
      <img src="./images/4.jpg" alt="">
    </div>
    <div class="about-text">
      <h2>Our History</h2>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aspernatur nostrum eaque maiores pariatur suscipit reprehenderit, vel distinctio dicta quo minima.</p>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aspernatur nostrum eaque maiores pariatur suscipit reprehenderit, vel distinctio dicta quo minima.</p>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aspernatur nostrum eaque maiores pariatur suscipit reprehenderit, vel distinctio dicta quo minima.</p>
      <a href="" class="btn">Learn more</a>
    </div>
  </section>
  <section class="guides" id="guides">
    <div class="heading">
      <h2>Our Guides</h2>
    </div>
    <div class="guides-container">
      <div class="box">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, dolorem. Ea maiores odit dolor aut!</p>
        <h2>Yasin Ahmed</h2>
        <img src="./images/p1.jpg" alt="">
      </div>
      <div class="box">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, dolorem. Ea maiores odit dolor aut!</p>
        <h2>Yasin Ahmed</h2>
        <img src="./images/p2.jpg" alt="">
      </div>
      <div class="box">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, dolorem. Ea maiores odit dolor aut!</p>
        <h2>Yasin Ahmed</h2>
        <img src="./images/p3.jpg" alt="">
      </div>
    </div>
  </section>
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

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>
