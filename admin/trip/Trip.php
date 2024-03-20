
<?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add Booking
  
$errors = array();

  if(isset($_POST['add_booking']))
    {
        $booking_id = $_POST['booking_id'];
        $user_id = $_POST['user_id'];
        $vehicle_id = $_POST['vehicle_id'];
        $driver_id = $_POST['driver_id'];
        $start_date = $_POST['start_date'];
        $start_loc = $_POST['start_loc'];
        $start_km = $_POST['start_km'];
        $end_date = $_POST['end_date'];
        $end_loc = $_POST['end_loc'];
        $end_km = $_POST['end_km'];
        $total_km = $_POST['total_km'];
        $amount = $_POST['amount'];
        $mobile_no = $_POST['mobile_no'];
        $status = $_POST['status'];
    
        // Perform validation (customize as per your requirements)
        if (empty($booking_id) || !is_numeric($booking_id)) {
            $errors['booking_id'] = 'Booking ID is required and must be numeric.';
        } else {
            // Check if the booking ID exists in the database
            $query = "SELECT * FROM booking WHERE booking_id = $booking_id";
            $result = mysqli_query($mysqli, $query);
        
            if (!$result) {
                die("Database query failed: " . mysqli_error($mysqli));
            }
        
            // Check if any rows were returned
            if (mysqli_num_rows($result) == 0) {
                $errors['booking_id'] = 'Booking ID does not exist.';
            }
        }
        
    
        if (empty($user_id) || !is_numeric($user_id)) {
            $errors['user_id'] = 'User ID is required and must be numeric.';
        } else {
            // Check if the user ID exists in the database
            $query = "SELECT * FROM users WHERE user_id = $user_id";
            $result = mysqli_query($mysqli, $query);
        
            if (!$result) {
                die("Database query failed: " . mysqli_error($mysqli));
            }
        
            // Check if any rows were returned
            if (mysqli_num_rows($result) == 0) {
                $errors['user_id'] = 'User ID does not exist.';
            }
        }
    
        if (empty($vehicle_id)) {
            $errors['vehicle_id'] = 'Vehicle ID is required.';
        }
    
        if (empty($driver_id) || !is_numeric($driver_id)) {
            $errors['driver_id'] = 'Driver ID is required and must be numeric.';
        }
    
        if (empty($start_date) || !strtotime($start_date)) {
            $errors['start_date'] = 'Start Date is required and must be a valid date and time format.';
        }
    
        if (empty($start_loc)) {
            $errors['start_loc'] = 'Start Location is required.';
        }
    
        if (!empty($start_km) && !is_numeric($start_km)) {
            $errors['start_km'] = 'Start Kilometer must be numeric.';
        }
    
        if (empty($end_date) || !strtotime($end_date)) {
            $errors['end_date'] = 'End Date is required and must be a valid date and time format.';
        }
    
        if (empty($end_loc)) {
            $errors['end_loc'] = 'End Location is required.';
        }
    
        if (!empty($end_km) && !is_numeric($end_km)) {
            $errors['end_km'] = 'End Kilometer is required and must be numeric.';
        }
    
        if (empty($total_km) || !is_numeric($total_km)) {
            $errors['total_km'] = 'Total Kilometer is required and must be numeric.';
        }
    
        if (!empty($amount) && !is_numeric($amount)) {
            $errors['amount'] = 'Amount must be numeric.';
        }
    
        if (empty($mobile_no)) {
            $errors['mobile_no'] = "Phone Number is required";
        } elseif (!preg_match("/^[7-9][0-9]{9}$/", $mobile_no)) {
            $errors['mobile_no'] = "Invalid phone number format";
        } 
    
        // Validate other fields as needed...
    
        if (empty($errors)) {
            // If there are no errors, insert data into the "trip" table
            $sql = "INSERT INTO trip (booking_id, user_id, vehicle_id, driver_id, start_date, start_loc, start_km, end_date, end_loc, end_km, total_km, amount, mobile_no, status)
                    VALUES ('$booking_id', '$user_id', '$vehicle_id', '$driver_id', '$start_date', '$start_loc', '$start_km', '$end_date', '$end_loc', '$end_km', '$total_km', '$amount', $mobile_no, '$status')";
            
            if ($mysqli->query($sql) === TRUE) {
                $succ = "Trip Added";
                echo '<script>window.location.assign("index.php");</script>';
            } else {
                echo 'Error: ' . $sql . '<br>' . $mysqli->error;
            }
    
            $mysqli->close();
        } 
    }
?>
 
 <!DOCTYPE html>
 <html lang="en">
<head>
    <style>
         .error {color: #FF0000;}
    </style>

<?php include('../vendor/inc/head.php');?>
</head>
 

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
                         <a href="#">Trip</a>
                     </li>
                     <li class="breadcrumb-item active">Add</li>
                 </ol>
                 <hr>
                 <div class="card">
                     <div class="card-header">
                         Add Trip
                     </div>
                     <div class="card-body">
                         <!--Add User Form-->         
                         <form method="POST">
                         <div class="form-group">
                <label for="booking_id">Booking ID:</label>
                <input type="text" id="booking_id" class= "form-control" name="booking_id" >
                <?php if (isset($errors['booking_id'])) echo "<span class='error'>* " . $errors['booking_id'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="user_id">User :</label>
                <input type="text" id="user_id" class= "form-control" name="user_id">
                <?php if (isset($errors['user_id'])) echo "<span class='error'>* " . $errors['user_id'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="vehicle_id">Vehicle :</label>
                <select id="vehicle_id" class= "form-control" name="vehicle_id">
                    <option value="">Select Vehicle ID</option>
                    <?php
                    // Query to fetch vehicle IDs from the vehicle table
                    $vehicleQuery = "SELECT vehicle_id FROM vehicles";
                    $vehicleResult = mysqli_query($mysqli, $vehicleQuery);

                    if ($vehicleResult) {
                        while ($row = mysqli_fetch_assoc($vehicleResult)) {
                            $vehicleID = $row['vehicle_id'];
                            echo '<option value="' . $vehicleID . '">' . $vehicleID . '</option>';
                        }
                    }
                    ?>
                </select>
                <?php if (isset($errors['vehicle_id'])) echo "<span class='error'>* " . $errors['vehicle_id'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="driver_id">Driver :</label>
                <input type="text" id="driver_id" class= "form-control" name="driver_id" >
                <?php if (isset($errors['driver_id'])) echo "<span class='error'>* " . $errors['driver_id'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="datetime-local" id="start_date" class= "form-control" name="start_date" >
                <?php if (isset($errors['start_date'])) echo "<span class='error'>* " . $errors['start_date'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="start_loc">Start Location:</label>
                <input type="text" id="start_loc" class= "form-control" name="start_loc" >
                <?php if (isset($errors['start_loc'])) echo "<span class='error'>* " . $errors['start_loc'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="start_km">Start Kilometer:</label>
                <input type="text" id="start_km" class= "form-control" name="start_km" >
                <?php if (isset($errors['start_km'])) echo "<span class='error'>* " . $errors['start_km'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="datetime-local" id="end_date" class= "form-control" name="end_date" >
                <?php if (isset($errors['end_date'])) echo "<span class='error'>* " . $errors['end_date'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="end_loc">End Location:</label>
                <input type="text" id="end_loc" class= "form-control" name="end_loc" >
                <?php if (isset($errors['end_loc'])) echo "<span class='error'>* " . $errors['end_loc'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="end_km">End Kilometer:</label>
                <input type="text" id="end_km" class= "form-control" name="end_km" >
                <?php if (isset($errors['end_km'])) echo "<span class='error'>* " . $errors['end_km'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="total_km">Total Kilometer:</label>
                <input type="text" id="total_km" class= "form-control" name="total_km" >
                <?php if (isset($errors['total_km'])) echo "<span class='error'>* " . $errors['total_km'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" class= "form-control" name="amount" >
                <?php if (isset($errors['amount'])) echo "<span class='error'>* " . $errors['amount'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="mobile_no">Phone Number:</label>
                <input type="text" id="mobile_no" class= "form-control" name="mobile_no" >
                <?php if (isset($errors['mobile_no'])) echo "<span class='error'>* " . $errors['mobile_no'] . "</span>"; ?>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" class= "form-control" name="status" >
                    <option value="Waiting">Waiting</option>
                    <option value="Start">Start</option>
                    <option value="Cancel">Cancel</option>
                    <option value="Completed">Completed</option>
                    <option value="PartialCompleted">PartialCompleted</option>
                </select>
                <?php if (isset($errors['status'])) echo "<span class='error'>* " . $errors['status'] . "</span>"; ?>
            </div>

                             <button type="submit" name="add_booking" class="btn btn-success">Submit</button>
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
         <script src="../vendor/jquery/jquery.min.js"></script>
         <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

         <!-- Core plugin JavaScript-->
         <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

         <!-- Page level plugin JavaScript-->
         <script src="../vendor/chart.js/Chart.min.js"></script>
         <script src="../vendor/datatables/jquery.dataTables.js"></script>
         <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

         <!-- Custom scripts for all pages-->
         <script src="../vendor/js/sb-admin.min.js"></script>

         <!-- Demo scripts for this page-->
         <script src="../vendor/js/demo/datatables-demo.js"></script>
         <script src="../vendor/js/demo/chart-area-demo.js"></script>
         <!--INject Sweet alert js-->
         <script src="../vendor/js/swal.js"></script>
      
 </body>

 </html>