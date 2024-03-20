<?php
 include('vendor/inc/config.php');
$errors = [];

if (isset($_POST['submit_verification'])) {
    $token = $_POST['token'];

    if (empty($token)) {
        $errors['token'] = "Token is missing.";
    }

    // Use the correct key to check for errors
    if (empty($errors)) {
        $query = "SELECT * FROM pass_reset WHERE token = '$token'";
        $result = mysqli_query($mysqli, $query);
       
        if ($result && mysqli_num_rows($result) > 0) {
            
            header("Location: password_reset.php?token=$token");
            exit();
        }  else {
            $errors['token'] = "Invalid token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h2>Token Verification</h2>
    
    <?php
    // Display errors, if any
    if (!empty($errors)) {
        echo '<div style="color: red;">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
    ?>

    <form action="" method="POST">
        <label for="token">Verification Code</label>
        <input type="text" id="token" name="token" required>
        <button type="submit" name="submit_verification">Submit</button>
    </form>
</body>
</html>
