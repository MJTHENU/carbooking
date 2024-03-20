<?php
session_start();
include('../connection.php');

// Check if a user is logged in and allowed to update their details (you might need more robust authentication)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to the login page if not logged in
    exit();
}

// Function to check if specific keys are present in the POST data and validate the associated data
function validateAndSanitizeKeys($post, $keys) {
    $validatedData = array();

    foreach ($keys as $key => $validationType) {
        if (isset($post[$key])) {
            $value = $post[$key];
            $filteredValue = filter_var(trim($value), FILTER_SANITIZE_STRING);

            // Perform additional validation based on $validationType if needed
            if ($validationType === 'email') {
                if (filter_var($filteredValue, FILTER_VALIDATE_EMAIL)) {
                    $validatedData[$key] = $filteredValue;
                } else {
                    return false; // Invalid email, stop processing
                }
            } elseif ($validationType === 'number') {
                if (is_numeric($filteredValue)) {
                    $validatedData[$key] = $filteredValue;
                } else {
                    return false; // Invalid number, stop processing
                }
            } else {
                $validatedData[$key] = $filteredValue;
            }
        } else {
            return false; // Missing key, stop processing
        }
    }

    return $validatedData;
}

// Define an array of expected keys in the POST data and their validation types
$expectedKeys = array(
    'user_id' => 'string',
    'first_name' => 'string',
    'last_name' => 'string',
    'email' => 'email',
    'date_of_birth' => 'string',
    'mobile_no' => 'string',
    'user_type' => 'string',
    'gender' => 'string'
);

// Validate the expected keys and sanitize their associated data
$validatedData = validateAndSanitizeKeys($_POST, $expectedKeys);

if ($validatedData !== false) {
    // All data is valid, proceed with database update
    // Handle form submission
    $user_id = $validatedData['user_id'];
    $first_name = $validatedData['first_name'];
    $last_name = $validatedData['last_name'];
    $email = $validatedData['email'];
    $age = $validatedData['date_of_birth'];
    $mobile_no = $validatedData['mobile_no'];
    $password = $validatedData['password'];
    $user_type = $validatedData['user_type'];
    $gender = $validatedData['gender'];

    // Perform validation on the updated data (similar to your registration form validation)

    // Debugging: Print SQL query
    echo "SQL Query: $sql<br>";

    // Update user details in the database
    $sql = "UPDATE Users SET 
                first_name = '$first_name', 
                last_name = '$last_name', 
                email = '$email', 
                age = '$age', 
                mobile_no = '$mobile_no', 
                password = '$password', 
                gender = '$gender' 
            WHERE user_id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("User details updated successfully");</script>';
        echo '<script>location.replace("../user/index.php");</script>';
    } else {
        echo "Error updating user details: " . $conn->error;
    }
} else {
    echo "Invalid data submitted. Please make sure all required fields are provided and valid.";
}

// Debugging: Print user_id from session
echo "Session user_id: " . $_SESSION['user_id'] . "<br>";

// Retrieve the user's current details from the database
$user_id = $_SESSION['user_id'];

// Debugging: Print SQL query
$sql = "SELECT * FROM Users WHERE user_id = $user_id"; 
echo "SQL Query: $sql<br>";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle error if the user is not found
    echo "User not found.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Details</title>
    <!-- Add your CSS and Bootstrap links here -->
</head>
<body>
    <?php include('../menu/header.php'); ?>
    <div>
        <?php include('../menu/sidemenu.php'); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card" style="width: 50rem;">
                        <div class="card-header">
                            <h1>Update User Details</h1>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                
                                <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>"><br><br>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>"><br><br>
                                </div>
                                <div class="form-group">
                                    <label for="email"> Email:</label>
                                    <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>"><br><br>
                                </div>
                                <div class="form-group">
                                    <label for="age"> Age:</label>
                                    <input type="text" id="age" name="age" value="<?php echo $user['age']; ?>"><br><br>
                                </div>
                                <div class="form-group">
                                    <label for="mobile_no"> Mobile No:</label>
                                    <input type="text" id="mobile_no" name="mobile_no" value="<?php echo $user['mobile_no']; ?>"><br><br>
                                </div>
                                <div class="form-group">       
                                        <label for="user_type">User Type:</label>
                                        <select id="user_type" name="user_type" >
                                            <option value="user">User</option>
                                            <option value="driver">Driver</option>
                                            <option value="admin">Admin</option>
                                        </select><br><br>
                                </div>
                                <div class="form-group">
                                    <label for="gender"> Gender:</label>
                                    <input type="text" id="gender" name="gender" value="<?php echo $user['gender']; ?>"><br><br>
                                </div>

                                
                                
                                <input type="submit" class="btn btn-primary" value="Update">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../menu/footer.php'); ?>
</body>
</html>
