<?php
session_start();
include('vendor/inc/config.php');

if (isset($_GET['user_id'])) {
    $edit = $_GET['user_id'];

    $sql = "SELECT * FROM users WHERE user_id = '$edit'";
    $run = mysqli_query($mysqli, $sql);

    if ($run && mysqli_num_rows($run) > 0) {
        $row = mysqli_fetch_assoc($run);
        $user_id = $row['user_id'];
        // Don't retrieve the password for security reasons
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Edit parameter not provided in the URL.";
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $oldPassword = md5($_POST['old_password']);
    $newPassword = md5($_POST['new_password']);
    $confirmPassword = md5($_POST['password']);

    // Validate old password (you might want to check against the actual old password stored in the database)
    if (empty($oldPassword)) {
        $errors['old_password'] = "Old Password is required";
    } elseif ($oldPassword !== $row['password']) {
        $errors['old_password'] = "Old Password is incorrect";
    }

    // Validate new password
    if (empty($newPassword)) {
        $errors['new_password'] = "New Password is required";
    } elseif (strlen($newPassword) < 8) {
        $errors['new_password'] = "New Password must be at least 8 characters long";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors['password'] = "Confirm Password is required";
    } elseif ($newPassword !== $confirmPassword) {
        $errors['password'] = "New Password and Confirm Password do not match";
    }

    if (empty($errors)) {
        // Update the database
        $sql = "UPDATE users SET password = '$newPassword' WHERE user_id = '$edit'";

        if ($mysqli->query($sql) === TRUE) {
            echo '<script>alert("Password updated successfully."); location.replace("index.php")</script>';
            exit();
        } else {
            echo 'Error: ' . $sql . '<br>' . $mysqli->error;
        }

        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel = "stylesheet" href = "vendor/css/user.css">
    <?php include("vendor/inc/head.php");  ?>
    <style>
        input{
            border: 2px solid;
        }
    </style>
</head>
<body>
    <?php  include("vendor/inc/nav.php"); ?>
    <div class = "user-form">
    <form action="" method="POST">
        <div class="form-group">
            <label for="old_password">Old Password:</label>
            <input type="password" id="old_password" class="form-control" name="old_password">
            <?php if (isset($errors['old_password'])) echo "<span class='error'>* " . $errors['old_password'] . "</span>"; ?>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" class="form-control" name="new_password">
            <?php if (isset($errors['new_password'])) echo "<span class='error'>* " . $errors['new_password'] . "</span>"; ?>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" class="form-control" name="password">
            <?php if (isset($errors['password'])) echo "<span class='error'>* " . $errors['password'] . "</span>"; ?>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update Password</button>
    </form>
    </div>
</body>
</html>
