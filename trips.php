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

// Check if the 'highlight_id' parameter exists in the URL
$highlight_id = isset($_GET['highlight_id']) ? $_GET['highlight_id'] : null;

// Handle search query
$search_location = isset($_GET['location']) ? $_GET['location'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$duration = isset($_GET['duration']) ? $_GET['duration'] : '';

// SQL query with search filters
$sql = "SELECT * FROM travel_packages WHERE 1";

if (!empty($search_location)) {
    $sql .= " AND location_name LIKE '%$search_location%'";
}

if (!empty($min_price)) {
    $sql .= " AND price >= '$min_price'";
}

if (!empty($max_price)) {
    $sql .= " AND price <= '$max_price'";
}

if (!empty($duration)) {
    $sql .= " AND duration = '$duration'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Trips</title>
    <link rel="stylesheet" href="./css/landing.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .btn-view {
            background-color: #007bff;
            color: white;
        }
        .btn-view:hover {
            background-color: #0056b3;
        }
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
        }
        h5 {
            text-transform: capitalize;
        }
        .highlight {
            border: 3px solid #007bff; /* Highlight border color */
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.5);
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

    <!-- Search Form -->
    <div class="container mt-4">
        <form method="GET" action="trips.php">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="location" class="form-control" placeholder="Search by location" value="<?php echo $search_location; ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?php echo $min_price; ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?php echo $max_price; ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="duration" class="form-control" placeholder="Duration (days)" value="<?php echo $duration; ?>">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <div class="col-md-1">
                <a href="trips.php" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Display Available Trips -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Available Trips</h1>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Highlight the relevant trip if the ID matches the highlight_id -->
                    <?php $highlightClass = ($highlight_id == $row['id']) ? 'highlight' : ''; ?>
                    <div class="col-md-4">
                        <div class="card <?php echo $highlightClass; ?>">
                            <img src="<?php echo !empty($row['image']) ? $row['image'] : 'path_to_default_image/default.jpg'; ?>" class="card-img-top" alt="Location Image">
                            <div class="card-body">
                                <h5 class="card-title m-title"><?php echo ucwords($row['location_name']); ?></h5>
                                <p class="card-text"><?php echo $row['description']; ?></p>
                                <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
                                <p><strong>Date:</strong> <?php echo $row['travel_date']; ?></p>
                                <p><strong>Duration:</strong> <?php echo $row['duration']; ?> days</p>
                                <!-- View Trip Button with highlight functionality -->
                                <a href="trip_details.php?package_id=<?php echo $row['id']; ?>" class="btn btn-view">View Trip</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <p class="text-center">No trips available for the selected filters.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
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
        </div>
        <div class="footer-box">
            <h3>View Guides</h3>
            <li><a href="#"></a>Features</li>
            <li><a href="#"></a>Careers</li>
            <li><a href="#"></a>Blog Post</li>
            <li><a href="#"></a>Our Branches</li>
        </div>
    </section>
<!-- Include Bootstrap JS (optional) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>
