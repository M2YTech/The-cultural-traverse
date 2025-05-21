<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// You can add additional code here to fetch and display user-specific data
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
    <!-- Include Bootstrap or other CSS framework -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
        }
       
        .sidebar {
            width: 280px;
            background-color: #f8f9fa;
            height: 100vh;
            padding-top: 20px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .sidebar img{
            display: inline;
            width: 40px;
            position: absolute;
        }
        .sidebar h4{
            font-size: 1.5rem;
            margin-left: 53px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #000;
        }
        .sidebar a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <!-- Side Navigation Panel -->
    <div class="sidebar">
        <img src="./images/logo.png"  width="50px" alt="">
        <h4>User Panel</h4>
        <a href="update_personal_info.php">Update Personal Information</a>
        <a href="travel_preferences.php">Travel Preferences</a>
        <a href="past_experiences.php">Past Travel Experiences</a>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <h1>User Panel</h1>
        <p>Welcome to your user panel. Use the side panel to manage your profile.</p>
    </div>
</body>
</html>
