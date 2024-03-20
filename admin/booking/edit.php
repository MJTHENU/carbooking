
<?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add USer
  if (isset($_GET['booking_id'])) {
    $edit = $_GET['booking_id'];
    
    // Retrieve the vehicle details from the database
    $sql = "SELECT * FROM booking WHERE booking_id = '$edit'";
    $run = mysqli_query($mysqli, $sql);

    if ($run && mysqli_num_rows($run) > 0) {
        $row = mysqli_fetch_assoc($run);
        $booking_id = $row['booking_id'];
        $customer_name = $row['customer_name'];
        $mobile_no = $row['mobile_no'];
        $user_id = $row['user_id'];
        $start_date = $row['start_date'];
        $start_loc = $row['start_loc'];
        $end_date = $row['end_date'];
        $end_loc = $row['end_loc'];
        $address = $row['address'];
        $vehicle_id = $row['vehicle_id'];
        $tariff_id = $row['tariff_id'];
        $drop_details = $row['drop_details'];
        $status = $row['status'];

    } else {
        echo "booking not found.";
        exit();
    }
} else {
    echo "Edit parameter not provided in the URL.";
    exit();
}

// Check if the form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $customer_name = $_POST['customer_name'];
    $mobile_no = $_POST['mobile_no'];
    $user_id = $_POST['user_id'];
    $start_date = $_POST['start_date'];
    $start_loc = $_POST['start_loc'];
    $end_date = $_POST['end_date'];
    $end_loc = $_POST['end_loc'];
    $address = $_POST['address'];
    $vehicle_id = $_POST['vehicle_id'];
    $tariff_id = $_POST['tariff_id'];
    $drop_details = $_POST['drop_details'];
    $status = $_POST['status'];

    $errors = array();

    if (empty($customer_name)) {
        $errors['customer_name'] = 'Customer Name is required.';
    }

    if (empty($mobile_no)) {
        $errors['mobile_no'] = 'Mobile Number is required.';
    } else {
        // Check if it's a numeric value and has exactly 10 digits
        if (!is_numeric($mobile_no) || strlen($mobile_no) !== 10) {
            $errors['mobile_no'] = 'Mobile Number must be a 10-digit numeric value.';
        }
    }

    if (empty($user_id) || !is_numeric($user_id)) {
        $errors['user_id'] = 'Customer ID is required and must be numeric.';
    }

    if (empty($start_date) || !strtotime($start_date)) {
        $errors['start_date'] = 'Travel Date is required and must be a valid date and time format.';
    }

    if (empty($end_date) || !strtotime($end_date)) {
        $errors['end_date'] = 'Travel End Date is required and must be a valid date and time format.';
    }

    if (empty($start_loc)) {
        $errors['start_loc'] = 'Pickup Location is required.';
    }

    if (empty($end_loc)) {
        $errors['end_loc'] = 'Drop Location is required.';
    }

    if (empty($vehicle_id) || !preg_match('/^[A-Za-z0-9\s]+$/', $vehicle_id)) {
        $errors['vehicle_id'] = 'Please select a valid Vehicle ID from the dropdown.';
    }    

    if (empty($tariff_id)){
        $errors['tariff_id'] = 'Tariff id is required.';
    }

    if (empty($status)) {
        $errors['status'] = 'Status is required.';
    }

    if (empty($errors)) {
        // Update the database
        $sql = "UPDATE booking SET customer_name = '$customer_name', mobile_no = '$mobile_no', user_id = '$user_id', start_date = '$start_date', start_loc = '$start_loc',
        end_date = '$end_date', end_loc = '$end_loc', address = '$address', vehicle_id = '$vehicle_id', tariff_id = '$tariff_id', drop_details = '$drop_details', status = '$status' WHERE booking_id = '$edit'";

        if ($mysqli->query($sql) === TRUE) {
            echo '<script>location.replace("index.php")</script>';
        } else {
            echo 'Error: ' . $sql . '<br>' . $mysqli->error;
        }

        $mysqli->close();
    } 
}
?>
 <!DOCTYPE html>
 
 <html lang="en">
    <style>
         .error {color: #FF0000;}
    </style>
 <?php include('../vendor/inc/head.php');?>

 <body id="page-top">
     <!--Start Navigation Bar-->
     <?php include("../vendor/inc/nav.php");?>
     <!--Navigation Bar-->

     <div id="wrapper">
    
         <!-- Sidebar -->
         <?php include("../vendor/inc/sidebar.php");?>
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
                         <a href="#">Booking</a>
                     </li>
                     <li class="breadcrumb-item active">Update Booking</li>
                 </ol>
                 <hr>
                 <div class="card">
                     <div class="card-header">
                         Update Booking
                     </div>
                     <div class="card-body">
                         <!--Add User Form-->
            
                         <form action="edit.php?booking_id=<?php echo $edit; ?>" method="POST">
                            <div class="form-group">
                                    <label for="customer_name">Customer Name:</label>
                                    <input type="text" id="customer_name" class = "form-control" name="customer_name" value="<?php echo $customer_name ?>">
                                    <?php if (isset($errors['customer_name'])) echo "<span class='error'>* " . $errors['customer_name'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="mobile_no">Mobile Number:</label>
                                    <input type="text" id="mobile_no" class = "form-control" name="mobile_no" value="<?php echo $mobile_no ?>" >
                                    <?php if (isset($errors['mobile_no'])) echo "<span class='error'>* " . $errors['mobile_no'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="user_id">Customer ID:</label>
                                    <input type="text" id="user_id" class = "form-control" name="user_id" value="<?php echo $user_id ?>" >
                                    <?php if (isset($errors['user_id'])) echo "<span class='error'>* " . $errors['user_id'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Travel Date:</label>
                                    <input type="datetime-local" id="start_date" class = "form-control" name="start_date" value="<?php echo $start_date ?>" >
                                    <?php if (isset($errors['start_date'])) echo "<span class='error'>* " . $errors['start_date'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="start_loc">Pickup Location:</label>
                                    <input type="text" id="start_loc" class = "form-control" name="start_loc" value="<?php echo $start_loc ?>" >
                                    <?php if (isset($errors['start_loc'])) echo "<span class='error'>* " . $errors['start_loc'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="end_date">Travel End Date:</label>
                                    <input type="datetime-local" id="end_date" class = "form-control" name="end_date" value="<?php echo $end_date ?>" >
                                    <?php if (isset($errors['end_date'])) echo "<span class='error'>* " . $errors['end_date'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="end_loc">Drop Location:</label>
                                    <input type="text" id="end_loc" class = "form-control" name="end_loc" value="<?php echo $end_loc ?>" >
                                    <?php if (isset($errors['end_loc'])) echo "<span class='error'>* " . $errors['end_loc'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" class = "form-control" name="address" value="<?php echo $address ?>">
                                    <?php if (isset($errors['address'])) echo "<span class='error'>* " . $errors['address'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle:</label>
                                    <select id="vehicle_id" class="form-control" name="vehicle_id">
                                        <option value="">Select Vehicle ID</option>
                                        <?php
                                        // Query to fetch vehicle IDs from the vehicle table
                                        $vehicleQuery = "SELECT vehicle_id FROM vehicles";
                                        $vehicleResult = mysqli_query($mysqli, $vehicleQuery);

                                        if ($vehicleResult) {
                                            while ($row = mysqli_fetch_assoc($vehicleResult)) {
                                                $vehicleID = $row['vehicle_id'];
                                                $selected = ($vehicleID == $vehicle_id) ? 'selected' : ''; // Check if it should be selected

                                                echo '<option value="' . $vehicleID . '" ' . $selected . '>' . $vehicleID . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php if (isset($errors['vehicle_id'])) echo "<span class='error'>* " . $errors['vehicle_id'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="tariff_id">Tariff Name:</label>
                                    <input type="text" id="tariff_id" class = "form-control" name="tariff_id" value="<?php echo $tariff_id ?>" >
                                    <?php if (isset($errors['tariff_id'])) echo "<span class='error'>* " . $errors['tariff_id'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="drop_details">Drop Details:</label>
                                    <textarea type="text" id="drop_details" class = "form-control" name="drop_details" value="<?php echo $drop_details ?>" ><?php echo $drop_details ?></textarea>
                                    <?php if (isset($errors['drop_details'])) echo "<span class='error'>* " . $errors['drop_details'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" name="status" class = "form-control" >
                                        <option value="Open" <?php if ($status === 'Open') echo 'selected'; ?>>Open</option>
                                        <option value="Process" <?php if ($status === 'Process') echo 'selected'; ?>>Process</option>
                                        <option value="Confirm" <?php if ($status === 'Confirm') echo 'selected'; ?>>Confirm</option>
                                        <option value="Cancel" <?php if ($status === 'Cancel') echo 'selected'; ?>>Cancel</option>
                                    </select>
                                    <?php if (isset($errors['status'])) echo "<span class='error'>* " . $errors['status'] . "</span>"; ?>
                                </div>


                             <button type="submit" name="update" class="btn btn-success">Update Booking</button>
                         </form>
                         <!-- End Form-->
                        
                     </div>
                 </div>

                 <hr>

    
                 <!-- Sticky Footer -->
                 <?php include("../vendor/inc/footer.php");?>

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

         <script src="../vendor/js/sb-admin.min.js"></script>
         

 </body>

 </html>