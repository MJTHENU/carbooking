<?php
session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];

$delete = $_GET['del'];
$sql = "delete from users where user_id = '$delete'";

if( mysqli_query($mysqli, $sql)) {

    echo '<script>location.replace("../user/index.php")</script>';
}
else{
    echo "something Error" . $mysqli->error;
}


?>