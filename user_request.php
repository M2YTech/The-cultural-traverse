<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // If not logged in, redirect to the admin login page
    header("Location: admin_login.php");
    exit();
}

$message = ''; // Initialize the message variable
$message_type = ''; // Initialize the message type variable

// Approve or reject user request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        $sql = "UPDATE users SET is_approved=1 WHERE id='$user_id'";
    } else {
        $sql = "DELETE FROM users WHERE id='$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        if ($action == 'accept') {
            // Send confirmation email
            $sql_email = "SELECT email FROM users WHERE id='$user_id'";
            $result = $conn->query($sql_email);
            $row = $result->fetch_assoc();

            // Check if $row is not null and contains a valid email
            if ($row && filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                if (sendConfirmationEmail($row['email'])) {
                    $message = 'Confirmation email has been sent.';
                    $message_type = 'success';
                } else {
                    $message = "Message could not be sent. Please try again later.";
                    $message_type = 'danger';
                }
            } else {
                $message = "Error: User email not found or invalid.";
                $message_type = "danger";
            }
        } else {
            $message = "User request rejected successfully.";
            $message_type = "success";
        }
    } else {
        $message = "Error: " . $conn->error;
        $message_type = "danger";
    }
}

// Fetch pending requests
$sql = "SELECT * FROM users WHERE is_approved=0";
$result = $conn->query($sql);

function sendConfirmationEmail($email) {
    $mail = new PHPMailer(true);  // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'funandlearnpk@gmail.com';
        $mail->Password = 'wnsi txqk zoeb ijyf'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; 

        //Recipients
        $mail->setFrom('no-reply@example.com', 'CulturalTraverse');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true); 
        $mail->Subject = 'Signup Confirmation';
        $mail->Body    = 'Congratulations, your signup request has been approved!';

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log error if needed
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    <div class="container mt-5">
    <h1 class="mb-4 text-center" >User Requests</h1>

    <!-- Alert Message -->
    <?php if (isset($message)): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td>
                    <form action="user_request.php" method="POST" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
