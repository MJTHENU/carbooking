<?php
include('../vendor/inc/config.php');

if (isset($_POST['vehicle_id'])) {
    $vehicle_id = $_POST['vehicle_id'];

    $query = "SELECT model FROM vehicles WHERE vehicle_id = '$vehicle_id'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['model'];
    } else {
        echo 'Model Not Found';
    }
} else {
    echo 'Invalid Request';
}
?>
