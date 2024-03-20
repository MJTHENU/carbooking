
<?php  
session_start();
include("vendor/inc/config.php");

// Initialize variables to avoid "undefined variable" errors
$startLocation = $pickupDate = $endLocation = $dropOffDate = $selectedTariff = "";

// Check if session variables are set before using them
if (isset($_SESSION["startLocation"])) {
    $startLocation = $_SESSION["startLocation"];
}

if (isset($_SESSION["pickupDate"])) {
    $pickupDate = $_SESSION["pickupDate"];
}

if (isset($_SESSION["endLocation"])) {
    $endLocation = $_SESSION["endLocation"];
}

if (isset($_SESSION["dropOffDate"])) {
    $dropOffDate = $_SESSION["dropOffDate"];
}

if (isset($_SESSION["last_fetched_vehicle_id"])) {
    $vehicleId = $_SESSION["last_fetched_vehicle_id"];
}

if (isset($_SESSION["user_id"])) {
    $userID = $_SESSION["user_id"];
}
  
$userQuery = "SELECT * FROM users WHERE user_id = $userID";
$userResult = $mysqli->query($userQuery);

// Check if the query was successful
if ($userResult) {
    // Fetch the user data as an associative array
    $userData = $userResult->fetch_assoc();

    // Now you can use $userData to access user information
    $FirstName = $userData['first_name'];
    $email = $userData['email'];

    // ... (use other user data as needed)

    $userResult->free(); // Free up the result set
} else {
    // Handle the error, e.g., display an error message or log it
    echo 'Error: ' . $userQuery . '<br>' . $mysqli->error;
}

$errors = array();

if (isset($_POST['add_user'])) {
    $customer_name = $_POST['customer_name'];
    $mobile_no = $_POST['mobile_no'];
    $user_id = $_POST['user_id'];
    $start_date = $_POST['start_date'];
    $start_loc = $_POST['start_loc'];
    $end_date = $_POST['end_date'];
    $end_loc = $_POST['end_loc'];
    $address = $_POST['address'];
    $vehicle_id = $_POST['vehicle_id'];
    $tariff_id = $_POST['tariff_id'];
    $status = $_POST['status'];

    // Check if the vehicle is already booked on the selected date
    $checkAvailabilityQuery = "SELECT * FROM booking WHERE vehicle_id = '$vehicle_id' AND
                               (('$start_date' BETWEEN start_date AND end_date) OR
                                ('$end_date' BETWEEN start_date AND end_date) OR
                                (start_date BETWEEN '$start_date' AND '$end_date'))";

    $availabilityResult = $mysqli->query($checkAvailabilityQuery);

    if ($availabilityResult && $availabilityResult->num_rows > 0) {
        $errors['vehicle_id'] = 'Selected vehicle is already booked for the specified date range.';
    }

     // Perform validation
     if (empty($customer_name)) {
        $errors['customer_name'] = 'Customer Name is required.';
    }

    if (empty($mobile_no)) {
        $errors['mobile_no'] = 'Mobile Number is required.';
    } else {
        // Check if it's a numeric value and has exactly 10 digits
        if (!is_numeric($mobile_no) || strlen($mobile_no) !== 10) {
            $errors['mobile_no'] = 'Mobile Number must be a 10-digit numeric value.';
        }
    }

    if (empty($user_id) || !is_numeric($user_id)) {
        $errors['user_id'] = 'Customer ID is required and must be numeric.';
    }

    if (empty($start_date) || !strtotime($start_date)) {
        $errors['start_date'] = 'Travel Date is required and must be a valid date and time format.';
    }

    if (empty($end_date) || !strtotime($end_date)) {
        $errors['end_date'] = 'Travel End Date is required and must be a valid date and time format.';
    }

    if (empty($start_loc)) {
        $errors['start_loc'] = 'Pickup Location is required.';
    }

    if (empty($end_loc)) {
        $errors['end_loc'] = 'Drop Location is required.';
    }

    if (empty($vehicle_id) || !preg_match('/^[A-Za-z0-9\s]+$/', $vehicle_id)) {
        $errors['vehicle_id'] = 'Please select a valid Vehicle ID from the dropdown.';
    }    

    if (empty($tariff_id)){
        $errors['tariff_id'] = 'Tariff id is required.';
    }

    if (empty($status)) {
        $errors['status'] = 'Status is required.';
    }

    // If there are no errors, insert data into the database
    if (empty($errors)) {
        $sql = "INSERT INTO booking (customer_name, mobile_no, user_id, start_date, start_loc, end_date, end_loc, address, vehicle_id, tariff_id, status)
                VALUES ('$customer_name', '$mobile_no', '$user_id', '$start_date', '$start_loc', '$end_date', '$end_loc', '$address', '$vehicle_id', '$tariff_id', '$status')";
        
        if ($mysqli->query($sql) === TRUE) {
            // Set the flag for successful booking
            $bookingSuccessful = true;

            // Send confirmation email to the customer
            if (empty($errors) && isset($bookingSuccessful) && $bookingSuccessful) {
                // Create a new PHPMailer instance
                
                require ('../PHPMailer/PHPMailerAutoload.php');

                $mail = new PHPMailer;

                // Configure SMTP settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sathishTom9363@gmail.com';
                $mail->Password = 'dktj oaiz rgqc oxuv'; // Replace with your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Set sender and recipient
                $mail->setFrom('sathishmass206@gmail.com', 'sathish');
                $mail->addAddress($email); // Use the customer's email address from the booking

                // Set email content as HTML
                $mail->isHTML(true);

                // Set email subject
                $mail->Subject = 'Booking Confirmation';

                // Build the HTML body
                $mail->Body = '<html><body>';
                $mail->Body .= '<h2>Booking Details</h2>';
                $mail->Body .= '<p>Dear ' . $FirstName . ',</p>';
                $mail->Body .= '<p>Your booking has been confirmed. Here are the details:</p>';
                $mail->Body .= '<p>Start Location: ' . $start_loc . '</p>';
                $mail->Body .= '<p>Start Date: ' . $start_date . '</p>';
                $mail->Body .= '<p>End Location: ' . $end_loc . '</p>';
                $mail->Body .= '<p>End Date: ' . $end_date . '</p>';
                // Add more details as needed
                $mail->Body .= '</body></html>';

                // Send the email
                if (!$mail->send()) {
                    echo 'Message could not be sent.';
                    // Handle the error, e.g., display an error message or log it
                } else {
                    echo 'Message has been sent';
                }
            }
        } else {
            echo 'Error: ' . $sql . '<br>' . $mysqli->error;
        }

        $mysqli->close();
    } 
}
?>
<?php
// Check if tariff_id is set in the URL
if (isset($_GET['tariff_id'])) {
    // Get the tariff_id from the URL
    $tariffId = $_GET['tariff_id'];

    // Now you can use $tariffId as needed in your page
    echo 'Selected Tariff ID: ' . $tariffId;

    // Rest of your booking-form.php code...
} else {
    // Handle the case when tariff_id is not provided in the URL
    echo 'Tariff ID not provided.';
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include("vendor/inc/head.php");  ?>
<body>
    <?php  include("vendor/inc/nav.php");  ?>


                    <div class = "card" style = "margin-top: 90px;">
                        <form class = "book-form" method="POST">
                                <div class="form-group">
                                    <h2>Booking Details</h2><br>
                                    <hr><br>
                                    <label for="customer_name">Customer Name:</label>
                                    <input type="text" id="customer_name" class = "form-control" name="customer_name" value="<?php echo $FirstName ?>">
                                    <?php if (isset($errors['customer_name'])) echo "<span class='error'>* " . $errors['customer_name'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="mobile_no">Mobile Number:</label>
                                    <input type="text" id="mobile_no" class = "form-control" name="mobile_no" >
                                    <?php if (isset($errors['mobile_no'])) echo "<span class='error'>* " . $errors['mobile_no'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="user_id">Customer ID:</label>
                                    <input type="text" id="user_id" class = "form-control" name="user_id" value="<?php echo $userID ?>" >
                                    <?php if (isset($errors['user_id'])) echo "<span class='error'>* " . $errors['user_id'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Travel Date:</label>
                                    <input type="datetime-local" class = "form-control" id="start_date" name="start_date" value="<?php echo $pickupDate ?>" >
                                    <?php if (isset($errors['start_date'])) echo "<span class='error'>* " . $errors['start_date'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="start_loc">Pickup Location:</label>
                                    <input type="text" id="start_loc" class = "form-control" value="<?php echo $startLocation ?>" name="start_loc" >
                                    <?php if (isset($errors['start_loc'])) echo "<span class='error'>* " . $errors['start_loc'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="end_date">Travel End Date:</label>
                                    <input type="datetime-local" id="end_date" class = "form-control" name="end_date" value="<?php echo $dropOffDate ?>" >
                                    <?php if (isset($errors['end_date'])) echo "<span class='error'>* " . $errors['end_date'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="end_loc">Drop Location:</label>
                                    <input type="text" id="end_loc" class = "form-control" name="end_loc" value="<?php echo $endLocation ?>" >
                                    <?php if (isset($errors['end_loc'])) echo "<span class='error'>* " . $errors['end_loc'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" class = "form-control" name="address">
                                    <?php if (isset($errors['address'])) echo "<span class='error'>* " . $errors['address'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle:</label>
                                    <input type="text" id="vehicle_id" class = "form-control" name="vehicle_id" value="<?php echo $vehicleId ?>">
                                    <?php if (isset($errors['vehicle_id'])) echo "<span class='error'>* " . $errors['vehicle_id'] . "</span>"; ?>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="vehicle_id">Vehicle :</label>
                                    <select id="vehicle_id" class = "form-control" name="vehicle_id">
                                        <option value="">Select Vehicle ID</option>
                                        <?php
                                        // Query to fetch vehicle IDs from the vehicle table
                                        // $vehicleQuery = "SELECT vehicle_id FROM vehicles";
                                        // $vehicleResult = mysqli_query($mysqli, $vehicleQuery);

                                        // if ($vehicleResult) {
                                        //     while ($row = mysqli_fetch_assoc($vehicleResult)) {
                                        //         $vehicleID = $row['vehicle_id'];
                                        //         $selected = '';
                                                
                                        //         // Check if $_POST['vehicle_id'] is set and matches the current vehicle ID
                                        //         if (isset($_POST['vehicle_id']) && $_POST['vehicle_id'] == $vehicleID) {
                                        //             $selected = 'selected'; // Set 'selected' attribute
                                        //         }

                                        //         echo '<option value="' . $vehicleID . '" ' . $selected . '>' . $vehicleID . '</option>';
                                        //     }
                                        // }
                                        ?>
                                    </select>
                                    <?php if (isset($errors['vehicle_id'])) echo "<span class='error'>* " . $errors['vehicle_id'] . "</span>"; ?>
                                </div> -->

                                <div class="form-group">
                                    <label for="tariff_id">Tariff ID:</label>
                                    <input type="text" id="tariff_id" class = "form-control" name="tariff_id" value="<?php echo $tariffId ?>">
                                    <?php if (isset($errors['tariff_id'])) echo "<span class='error'>* " . $errors['tariff_id'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" name="status" class = "form-control" >
                                        <option value="Open">Open</option>
                                        <option value="Process">Process</option>
                                        <option value="Confirm">Confirm</option>
                                        <option value="Cancel">Cancel</option>
                                    </select>
                                    <?php if (isset($errors['status'])) echo "<span class='error'>* " . $errors['status'] . "</span>"; ?><br><br>
                                </div>
                             <button type="submit" name="add_user" class="btn btn-success btn-form">Submit</button>
                            </form>
    <?php include("vendor/inc/footer.php"); ?>
                                    </div>

                                    <script src="./vendor/js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        // Your existing JavaScript code for SweetAlert
        <?php
        // Check if booking is successful
        if ($bookingSuccessful) {
            echo 'Swal.fire({
                icon: "success",
                title: "Booking Successful",
                text: "Your booking has been confirmed!",
                confirmButtonText: "OK"
            }).then(() => {
                location.replace("index.php");
            });';
        }
        ?>
    </script>


</body>
</html>
