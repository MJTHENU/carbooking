<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
$aid = $_SESSION['user_id'];

// Fetch the seat count from the vehicle table
$sql_vehicle = "SELECT seat FROM vehicles WHERE status = 'Available'";
$result_vehicle = mysqli_query($mysqli, $sql_vehicle);

// Create an array to store the seat counts
$vehicle_seat_counts = array();

while ($row_vehicle = mysqli_fetch_assoc($result_vehicle)) {
    $vehicle_seat_counts[] = $row_vehicle['seat'];
}

// Convert the array to a comma-separated string
$seat_counts_str = implode(',', $vehicle_seat_counts);

?>
    
<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php include('vendor/inc/head.php'); ?>
<!-- End Head -->

<body id="page-top">
    <!-- Navbar -->
    <?php include('vendor/inc/nav.php'); ?>
    <!-- End Navbar -->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include('vendor/inc/sidebar.php'); ?>
        <!-- End Sidebar -->

        <div id="content-wrapper">

            <div class="container-fluid">
                <!-- Breadcrumbs -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="user-dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Tariff</li>
                    <li class="breadcrumb-item active">Book Tariff</li>

                </ol>

                <!-- Bookings -->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-bus"></i>
                        Available Tariffs with Matching Seats
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tariff Name</th>
                                        <th>Tariff ID</th>
                                        <th>Tariff Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Book</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php

                                    // Modify the SQL query to include seat count condition
                                    $sql = "SELECT * FROM tariff WHERE status = 'active' AND seat IN ($seat_counts_str)";
                                    $result = mysqli_query($mysqli, $sql);
                                    $cnt = 1;

                                    while ($row = mysqli_fetch_array($result)) {
                                        $tariff_name = $row['tariff_name'];
                                        $tariff_id = $row['tariff_id'];
                                        $tariff_type = $row['tariff_type'];
                                        $amount = $row['amount'];
                                        $status = $row['status'];
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $tariff_name; ?></td>
                                            <td><?php echo $tariff_id; ?></td>
                                            <td><?php echo $tariff_type; ?></td>
                                            <td><?php echo $amount; ?></td>
                                            <td><?php echo $status; ?></td>
                                            <td>
                                                <a href="confirm_tariff.php?tariff_id=<?php echo $tariff_id; ?>" class="btn btn-outline-success"><i class="fa fa-clipboard"></i> Choose</a>
                                            </td>
                                        </tr>
                                    <?php
                                        $cnt++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                                        <div class="card-footer small text-muted">
                        <?php
                        date_default_timezone_set("Asia/Kolkata"); // India/Tamil Nadu timezone
                        echo "Generated At : " . date("h:i:sa");
                        ?>
                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

            <!-- Sticky Footer -->
            <?php include("vendor/inc/footer.php"); ?>

        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="user-logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="vendor/js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page -->
    <script src="vendor/js/demo/datatables-demo.js"></script>
    <script src="vendor/js/demo/chart-area-demo.js"></script>

</body>

</html>
