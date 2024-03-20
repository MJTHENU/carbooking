<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<?php include("vendor/inc/head.php"); ?>

<body id="page-top">
    <!--Start Navigation Bar-->
    <?php include("vendor/inc/nav.php"); ?>
    <!--Navigation Bar-->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("vendor/inc/sidebar.php"); ?>
        <!--End Sidebar-->
        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="user-dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Booking</li>
                    <li class="breadcrumb-item ">View My Booking</li>
                </ol>

                <!-- My Bookings-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-table"></i>
                        Bookings
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th> Name</th>
                                        <th>Mob No</th>
                                        <th>Vehicle ID</th>
                                        <th>Travel Date</th>
                                        <th>Pickup Loc</th>
                                        <th>Tariff</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $aid = $_SESSION['user_id'];
                                    $ret = "SELECT * from booking where user_id=? ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('i', $aid);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    //$cnt=1;
                                    while ($row = $res->fetch_object()) {
                                        $status = $row->status; // Fetch the status
                                        if ($status == "Confirm") {
                                            $showStartTripButton = true;
                                        } else {
                                            $showStartTripButton = false;
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $row->booking_id; ?></td>
                                            <td><?php echo $row->customer_name; ?></td>
                                            <td><?php echo $row->mobile_no; ?></td>
                                            <td><?php echo $row->vehicle_id; ?></td>
                                            <td><?php echo $row->start_date; ?></td>
                                            <td><?php echo $row->start_loc; ?></td>
                                            <td><?php echo $row->tariff_id; ?></td>
                                            <td><?php echo $row->status; ?></td>
                                            <td>
                                                <?php if ($showStartTripButton) : ?>
                                                    <a href="starttrip.php?booking_id=<?php echo $row->booking_id; ?>" class="badge badge-warning"><i class="fa fa-car"></i>Start Trip</a>
                                                <?php endif; ?>
                                                <a href=" update-booking.php?booking_id=<?php echo $row->booking_id; ?>" class="badge badge-success"><i class="fa fa-user-edit"></i> Update</a>
                                                <a href="delete-booking.php?del=<?php echo $row->booking_id; ?>" class="badge badge-danger"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php  } ?>
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
                    <a class="btn btn-danger" href="user-logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript function to hide the "Start Trip" button when clicked
        document.addEventListener('DOMContentLoaded', function () {
            const startTripButtons = document.querySelectorAll('.start-trip-button');

            startTripButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Hide the clicked button
                    button.style.display = 'none';
                });
            });
        });
    </script>

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

</body>

</html>
