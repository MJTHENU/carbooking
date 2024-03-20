<?php
session_start();

// Include the file for database connection
require_once("vendor/inc/config.php");

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}


// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch user details from the database based on user ID
$userID = $_SESSION["user_id"];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
  <!-- divinectorweb.com -->
<head>
   <?php //  include("vendor/inc/head.php");  ?>
    <link rel = "stylesheet" href = "vendor/css/user.css">
</head>
<body>
    <?php // include("vendor/inc/nav.php"); ?>
<div class="wrapper">
        <div class="user-card">
            <div class="user-card-img">
                <!-- <img src="<?php // echo $user['profile_picture']; ?>" alt="User Avatar"> -->
            </div>
            <div class="user-card-info">
                <h2><?php echo $user['first_name']; ?></h2>
                <p><span>Email:</span> <?php echo $user['email']; ?></p>
                <p><span>Date Of Birth:</span> <?php echo $user['date_of_birth']; ?></p>
                <p><span>Phone:</span> <?php echo $user['mobile_no']; ?></p>
                <p><span>Password:</span> <?php echo $user['password']; ?></p>
                <button class="change-btn btn"><a href='change-password.php?user_id=<?php echo $userID; ?>'>Change Password</a></button>
                <button class = "forgot-btn btn"><a href = "#">Forgot Password</a></button>
            </div>
        </div>
    </div>
      
</body>
</html>
