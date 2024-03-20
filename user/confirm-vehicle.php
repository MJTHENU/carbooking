<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['user_id'];

if (isset($_POST['book_vehicle'])) {
    $bookdate = $_POST['bookdate'];

    // Check if the booking date is in the future and in the correct format (d-m-Y)
    $currentDate = date("d-m-Y");
    $inputDate = DateTime::createFromFormat('d-m-Y', $bookdate);

    if (empty($bookdate)) {
        $err = "bookdate is required";
    } else {
        // Check if the date is already booked for the selected vehicle
        $u_id = $_SESSION['user_id'];
        $vehicle_id = $_POST['vehicle_id'];

        $checksql = "SELECT * FROM vehicle_tariff WHERE vehicle_id = ? AND bookdate = ?";
        $stmt = $mysqli->prepare($checksql);

        if ($stmt === false) {
            die("Error in prepare statement: " . $mysqli->error);
        }

        $stmt->bind_param('is', $vehicle_id, $bookdate);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $err = "This date is already booked for the selected vehicle.";
        } else {
            // The booking date is valid and available, proceed with the booking
            $vehicle_name = $_POST['vehicle_name'];
            $model = $_POST['model']; // Added the model field
            $status = $_POST['status'];

            // Insert the booking record into the database
            $sql = "INSERT INTO vehicle_tariff (user_id, vehicle_id, vehicle_name, model, bookdate, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);

            if ($stmt === false) {
                die("Error in prepare statement: " . $mysqli->error);
            }

            $rc = $stmt->bind_param('isssss', $u_id, $vehicle_id, $vehicle_name, $model, $bookdate, $status);

            if ($rc === false) {
                die("Error in bind_param: " . $stmt->error);
            }

            $result = $stmt->execute();

            if ($result === false) {
                die("Error in execute: " . $stmt->error);
            }

            // Close the statement
            $stmt->close();

            // Provide success feedback to the user
            $succ = "Booking Submitted";
        }
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
                <?php if(isset($succ)) {?>
                <!--This code for injecting an alert-->
                <script>
                setTimeout(function() {
                        swal("Success!", "<?php echo $succ;?>!", "success");
                    },
                    100);
                </script>

                <?php } ?>
                <?php if(isset($err)) {?>
                <!--This code for injecting an alert-->
                <script>
                setTimeout(function() {
                        swal("Failed!", "<?php echo $err;?>!", "Failed");
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
                <div class "card">
                    <div class="card-header">
                        Confirm Booking
                    </div>
                    <div class="card-body">
                        <!--Add User Form-->
                        <?php
            $aid=$_GET['vehicle_id'];
            $ret="select * from vehicles where vehicle_id=?";
            $stmt= $mysqli->prepare($ret) ;
            $stmt->bind_param('s',$aid);
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;
            if($row=$res->fetch_object()) {
                $u_id = $_SESSION['user_id'];
        ?>
           
                        <form method="POST">
                        <div class="form-group">
                                <label for="exampleInputEmail1">user ID</label>
                                <input type="text" value="<?php echo $u_id;?>" readonly class="form-control" name="user_id">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Online Car Category</label>
                                <input type="text" value="<?php echo $row->vehicle_name;?>" readonly class="form-control" name="vehicle_name">
                            </div>
                            <div class="form-group">
                                <input type="hidden" value="<?php echo $row->vehicle_id;?>" readonly class="form-control" name="vehicle_id">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Model</label>
                                <input type="text" value="<?php echo $row->model;?>" readonly class="form-control" name="model">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Booking Date</label>
                                <input type="date" class="form-control" id="exampleInputEmail1" name="bookdate" required>
                            </div>
                            <div class="form-group" style="display:none">
                                <label for="exampleInputEmail1">Book Status</label>
                                <input type="text" value="active" class="form-control" id="exampleInputEmail1" name="status">
                            </div>

                            <button type="submit" name="book_vehicle" class="btn btn-success">Confirm Booking</button>
                        </form>
                        <!-- End Form-->
                        <?php }?>
                    </div>
                </div>

                <hr>

                <?php // Close the database connection (if needed)
        $mysqli->close(); ?>
        

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
