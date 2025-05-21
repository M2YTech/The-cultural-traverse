<?php
session_start();
require 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all reviews from the database
$sql = "SELECT * FROM past_experiences";
$result = $conn->query($sql);
$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f4f4f9;
            color: #333;
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
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Filter and Sort Section */
        .filter-sort-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter, .sort {
            display: flex;
            align-items: center;
        }

        label {
            margin-right: 8px;
            font-weight: bold;
        }

        select, button {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            cursor: pointer;
        }

        button {
            margin-left: 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Review Cards */
        .reviews-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .review-card {
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 8px;
            border-left: 6px solid #4CAF50;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .review-card h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .review-card p {
            margin-bottom: 15px;
            color: #555;
        }

        .review-card .review-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .review-card .rating {
            color: #FFD700;
        }

        .review-card .date {
            font-size: 0.9em;
            color: #777;
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
        <a href="admin_logout.php">Logout</a> <!-- Added a logout option -->
    </div>
    <div class="container">
        <h1>Admin - User Reviews</h1>

        <!-- Filter and Sort Section -->
        <div class="filter-sort-section">
            <div class="filter">
                <label for="ratingFilter">Filter by Rating:</label>
                <select id="ratingFilter" name="ratingFilter" onchange="applyFilterAndSort()">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
            <div class="sort">
                <label for="sortReviews">Sort by:</label>
                <select id="sortReviews" name="sortReviews" onchange="applyFilterAndSort()">
                    <option value="latest">Latest</option>
                    <option value="oldest">Oldest</option>
                    <option value="highest">Highest Rating</option>
                    <option value="lowest">Lowest Rating</option>
                </select>
            </div>
        </div>

        <!-- Reviews Section -->
        <div id="reviews" class="reviews-section">
            <!-- Dynamic content will go here -->
        </div>
    </div>

    <script>
        // Data from PHP
        const reviews = <?php echo json_encode($reviews); ?>;

        // Function to render reviews
        function renderReviews(reviews) {
            const reviewContainer = document.getElementById("reviews");
            reviewContainer.innerHTML = "";

            reviews.forEach(review => {
                const reviewCard = document.createElement("div");
                reviewCard.classList.add("review-card");

                reviewCard.innerHTML = `
                    <h3>${review.destination}</h3>
                    <h4>user: ${review.user_email}</h4>
                    <div class="review-meta">
                        <span class="rating">${"★".repeat(review.rating)}</span>
                        <span class="date">${review.date_of_tour}</span>
                    </div>
                    <p>${review.comment}</p>
                `;

                reviewContainer.appendChild(reviewCard);
            });
        }

        // Initial render
        renderReviews(reviews);

        // Function to apply filter and sort
        function applyFilterAndSort() {
            const ratingFilter = document.getElementById("ratingFilter").value;
            const sortType = document.getElementById("sortReviews").value;

            let filteredReviews = reviews;

            // Filter by rating
            if (ratingFilter) {
                filteredReviews = filteredReviews.filter(review => review.rating == ratingFilter);
            }

            // Sort by date or rating
            if (sortType === "latest") {
                filteredReviews.sort((a, b) => new Date(b.date_of_tour) - new Date(a.date_of_tour));
            } else if (sortType === "oldest") {
                filteredReviews.sort((a, b) => new Date(a.date_of_tour) - new Date(b.date_of_tour));
            } else if (sortType === "highest") {
                filteredReviews.sort((a, b) => b.rating - a.rating);
            } else if (sortType === "lowest") {
                filteredReviews.sort((a, b) => a.rating - b.rating);
            }

            renderReviews(filteredReviews);
        }
    </script>
</body>
</html>
