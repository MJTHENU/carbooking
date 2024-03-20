<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['user_id'];

if (isset($_POST['book_tariff'])) {
    $u_id = $_SESSION['user_id'];
    $tariff_id = $_POST['tariff_id'];
    $tariff_name = $_POST['tariff_name'];
    $status = 'active'; // Set the status to 'active'

    // Find the last inserted record for the user based on the created_at timestamp
    $query = "SELECT id FROM vehicle_tariff WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $u_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['id'];

        // Update the specific record with the new data
        $updateQuery = "UPDATE vehicle_tariff SET tariff_id = ?, tariff_name = ?, status = ? WHERE id = ?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param('issi', $tariff_id, $tariff_name, $status, $lastId);
        $stmt->execute();

        if ($stmt) {
            $succ = "Booking Submitted";
        } else {
            $err = "Please Try Again Later";
        }
    } else {
        $err = "No records found to update.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<body id="page-top">
    <!--Start Navigation Bar-->
    <?php include("vendor/inc/nav.php");?>
    <!--Navigation Bar-->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("vendor/inc/sidebar.php");?>
        <!--End Sidebar-->
        <div id="content-wrapper">

            <div class="container-fluid">
                <?php if (isset($succ)) { ?>
                <!--This code for injecting an alert-->
                <script>
                setTimeout(function() {
                        swal("Success!", "<?php echo $succ; ?>", "success");
                    },
                    100);
                </script>

                <?php } ?>
                <?php if (isset($err)) { ?>
                <!--This code for injecting an alert-->
                <script>
                setTimeout(function() {
                        swal("Failed!", "<?php echo $err; ?>", "error");
                    },
                    100);
                </script>

                <?php } ?>
                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="user-dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Online Car</li>
                    <li class="breadcrumb-item ">Book Online Car</li>
                    <li class="breadcrumb-item active">Confirm Booking</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        Confirm Booking
                    </div>
                    <div class="card-body">
                        <!--Add User Form-->
                        <?php
            $aid = $_GET['tariff_id'];
            $ret = "select * from tariff where tariff_id=?";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('i', $aid);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            //$cnt=1;
            while ($row = $res->fetch_object()) {
            ?>
                        <form method="POST">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tariff ID</label>
                                <input type="text" value="<?php echo $row->tariff_id; ?>" readonly class="form-control"
                                    name="tariff_id">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tariff Name</label>
                                <input type="email" value="<?php echo $row->tariff_name; ?>" readonly class="form-control"
                                    name="tariff_name">
                            </div>

                            <button type="submit" name="book_tariff" class="btn btn-success">Choose </button>
                        </form>
                        <!-- End Form-->
                        <?php } ?>
                    </div>
                </div>

                <hr>

                <!-- Sticky Footer -->
                <?php include("vendor/inc/footer.php");?>

            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <!-- Logout Modal-->
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
                        <a class="btn btn-danger" href="admin-logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Page level plugin JavaScript-->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="vendor/js/sb-admin.min.js"></script>

        <!-- Demo scripts for this page-->
        <script src="vendor/js/demo/datatables-demo.js"></script>
        <script src="vendor/js/demo/chart-area-demo.js"></script>
        <!--INject Sweet alert js-->
        <script src="vendor/js/swal.js"></script>

</body>

</html>
