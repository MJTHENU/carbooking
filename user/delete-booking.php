<?php
session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid = $_SESSION['user_id'];

$delete = $_GET['del'];
$sql = "delete from booking where booking_id = '$delete'";

if( mysqli_query($mysqli, $sql)) {

    echo '<script>location.replace("view-booking.php")</script>';
}
else{
    echo "something Error" . $mysqli->error;
}


?>