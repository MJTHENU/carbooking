<?php
session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
?>
<?php  
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    

    // Retrieve common data from the booking table
    $select_booking_sql = "SELECT booking_id, user_id, vehicle_id, start_date, start_loc, end_date, end_loc, mobile_no FROM booking WHERE booking_id = $booking_id";
    $booking_result = mysqli_query($mysqli, $select_booking_sql);
    $booking_data = mysqli_fetch_assoc($booking_result);

    if ($booking_data) {
        // Insert data into the trip table
        $booking_id = $booking_data['booking_id'];
        $user_id = $booking_data['user_id'];
        $vehicle_id = $booking_data['vehicle_id'];
        $start_date = $booking_data['start_date'];
        $start_loc = $booking_data['start_loc'];
        $end_date = $booking_data['end_date'];
        $end_loc = $booking_data['end_loc'];
        $mobile_no = $booking_data['mobile_no'];
        $status = 'Start'; // You can set the initial status for the trip here

        $insert_trip_sql = "INSERT INTO trip (booking_id, user_id, vehicle_id, start_date, start_loc, end_date, end_loc, mobile_no, status) 
                            VALUES ('$booking_id', '$user_id', '$vehicle_id', '$start_date', '$start_loc', '$end_date', '$end_loc', '$mobile_no', '$status')";
        
        if (mysqli_query($mysqli, $insert_trip_sql)) {
            // Get the auto-generated trip ID
            $trip_id = mysqli_insert_id($mysqli);

            // Redirect to the edit trip page with the trip ID as a parameter
            header("Location: ../trip/edit.php?trip_id=$trip_id");
            exit();
        } else {
            echo "Error creating trip: " . mysqli_error($mysqli);
        }
    } else {
        echo "Booking not found.";
    }
} else {
    echo "Booking ID not provided.";
}
?>
