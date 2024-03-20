<?php 
include('../vendor/inc/config.php');

if (isset($_POST['vehicle_name'])) {
    $vehicle_name = $_POST['vehicle_name'];

    $query = "SELECT tariff_id FROM tariff WHERE tariff_name = '$vehicle_name'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['tariff_id'];
    } else {
        echo 'Tariff Name Not Found';
    }
} else {
    echo 'Invalid Request';
}

?>