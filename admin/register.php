<?php 
include('connection.php');
session_start();
?>
<?php
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = validateInput($_POST['first_name']);
    $email = validateInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($first_name)) {
        $errors['first_name'] = "Username is required";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $first_name)) {
        $errors['first_name'] = "Username should only contain letters, numbers, and underscores";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }else {
        
        $emailExistsQuery = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($emailExistsQuery);
        if ($result->num_rows > 0) {
            $errors['email'] = "Email already exists";
        }
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long";
    }

    if (empty($errors)) {

        $sql = "INSERT INTO users (	first_name, email, password) VALUES ('$first_name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
         .error {color: #FF0000;}
    </style>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <label for="first_name">Username</label>
        <input type="text" name="first_name" placeholder="first_name" >
        <?php if (isset($errors['first_name'])) echo "<span class='error'>* " . $errors['first_name'] . "</span>"; ?><br><br>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Email" >
        <?php if (isset($errors['email'])) echo "<span class='error'>* " . $errors['email'] . "</span>"; ?><br><br>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" >
        <?php if (isset($errors['password'])) echo "<span class='error'>* " . $errors['password'] . "</span>"; ?><br>
        <input type="submit" value="Register">
    </form>
    <p>Do you have an account? <a href="index.php">Login</a></p>
</body>
</html>
