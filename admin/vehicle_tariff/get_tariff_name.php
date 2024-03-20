<?php 
include('../vendor/inc/config.php');

if (isset($_POST['tariff_id'])) {
    $tariff_id = $_POST['tariff_id'];

    $query = "SELECT tariff_name FROM tariff WHERE tariff_id = '$tariff_id'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['tariff_name'];
    } else {
        echo 'Tariff Name Not Found';
    }
} else {
    echo 'Invalid Request';
}

?>