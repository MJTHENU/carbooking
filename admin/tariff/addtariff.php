

<?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add USer
$error_messages = array();

  if(isset($_POST['add_tariff']))
    {
        $tariff_name = $_POST['tariff_name'];
    $tariff_type = $_POST['tariff_type'];
    $amount = $_POST['amount'];
    $min_km = $_POST['min_km'];
    $per_km = $_POST['per_km'];
    $extra_km = $_POST['extra_km'];
    $seat = $_POST['seat'];
    $driver_charge = $_POST['driver_charge'];
    $expensive = $_POST['expensive'];
    $status = $_POST['status'];
    
    // Perform validation
    $errors = array();

    if (empty($tariff_name)) {
        $errors['tariff_name'] = "Tariff Name is required.";
    } elseif (!preg_match('/^[A-Za-z0-9\s]+$/', $tariff_name)) {
        $errors['tariff_name'] = "Tariff Name should contain only letters, numbers, and spaces.";
    }

    if (empty($tariff_type)) {
        $errors['tariff_type'] = "Tariff Type is required.";
    } elseif (!in_array($tariff_type, ['per_hour', 'per_km', 'per_day'])) {
        $errors['tariff_type'] = "Invalid Tariff Type selected.";
    }

    if (!empty($amount) && !is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = "Amount must be a valid positive number.";
    }
    
    if (!empty($min_km) && (!is_numeric($min_km) || $min_km < 0)) {
        $errors['min_km'] = "Minimum Kilometers must be a valid non-negative number.";
    }
    
    if (!empty($per_km) && (!is_numeric($per_km) || $per_km <= 0)) {
        $errors['per_km'] = "Per Kilometer Rate must be a valid positive number.";
    }
    
    if (!empty($extra_km) && (!is_numeric($extra_km) || $extra_km <= 0)) {
        $errors['extra_km'] = "Extra Kilometer Rate must be a valid positive number.";
    }

    if (empty($seat)) {
        $errors['seat'] = 'Seat is required.';
    } elseif (!is_numeric($seat) || $seat <= 0) {
        $errors['seat'] = 'Seat must be a valid number greater than 0.';
    }
    
    if (!empty($driver_charge) && (!is_numeric($driver_charge) || $driver_charge <= 0)) {
        $errors['driver_charge'] = "Driver Charge must be a valid positive number.";
    }
    
    if (!empty($expensive) && (!is_numeric($expensive) || $expensive <= 0)) {
        $errors['expensive'] = "Expensive Rate must be a valid positive number.";
    }
    

    if (empty($status) || !in_array($status, ['active', 'inactive'])) {
        $errors['status'] = "Invalid Status selected.";
    }

    if (empty($errors)) {
        // Insert data into the database
        $sql = "INSERT INTO tariff (tariff_name, tariff_type, amount, min_km, per_km, extra_km, seat, driver_charge, expensive, status)
                VALUES ('$tariff_name', '$tariff_type', '$amount', '$min_km', '$per_km', '$extra_km', '$seat', '$driver_charge', '$expensive', '$status')";

        if ($mysqli->query($sql) === TRUE) {
            echo '<script>location.replace("index.php")</script>';  
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

        // Close the database connection
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
                         <a href="#">Drivers</a>
                     </li>
                     <li class="breadcrumb-item active">Add Driver</li>
                 </ol>
                 <hr>
      
                 <div class="card">
                     <div class="card-header">
                         Add Driver
                     </div>
                     <div class="card-body">
                         <!--Add User Form-->
                         <form method="POST">
                         <div class="form-group">
                                <label for="tariff_name">Tariff Name:</label>
                                <input type="text" id="tariff_name" class = "form-control" name="tariff_name" >
                                <?php if (isset($errors['tariff_name'])) echo "<span class='error'>* " . $errors['tariff_name'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="tariff_type">Tariff Type:</label>
                                <select id="tariff_type" name="tariff_type" class = "form-control" >  
                                    <option value=""></option> 
                                    <option value="per_hour">Per Hour</option>
                                    <option value="per_km">Per Kilometer</option>
                                    <option value="per_day">Per Day</option>
                                </select>
                                <?php if (isset($errors['tariff_type'])) echo "<span class='error'>* " . $errors['tariff_type'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="number" id="amount" class = "form-control" name="amount" >
                                <?php if (isset($errors['amount'])) echo "<span class='error'>* " . $errors['amount'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="min_km">Minimum Kilometers:</label>
                                <input type="number" id="min_km" class = "form-control" name="min_km" >
                                <?php if (isset($errors['min_km'])) echo "<span class='error'>* " . $errors['min_km'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="per_km">Per Kilometer Rate:</label>
                                <input type="number" id="per_km" class = "form-control" name="per_km" >
                                <?php if (isset($errors['per_km'])) echo "<span class='error'>* " . $errors['per_km'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="extra_km">Extra Kilometer Rate:</label>
                                <input type="number" id="extra_km" class = "form-control" name="extra_km" >
                                <?php if (isset($errors['extra_km'])) echo "<span class='error'>* " . $errors['extra_km'] . "</span>"; ?>
                                </div>

                                <div class ="form-group">
                                <label for="seat">Seat:</label>
                                <input type="number" id="seat" class = "form-control" name="seat">
                                <?php if (isset($errors['seat'])) echo "<span class='error'>* " . $errors['seat'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="driver_charge">Driver Charge:</label>
                                <input type="number" id="driver_charge" class = "form-control" name="driver_charge" >
                                <?php if (isset($errors['driver_charge'])) echo "<span class='error'>* " . $errors['driver_charge'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="expensive">Expensive Rate:</label>
                                <input type="number" id="expensive" class = "form-control" name="expensive" >
                                <?php if (isset($errors['expensive'])) echo "<span class='error'>* " . $errors['expensive'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="status">Status:</label>
                                <select id="status" name="status" class = "form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <?php if (isset($errors['status'])) echo "<span class='error'>* " . $errors['status'] . "</span>"; ?>
                                </div>
                             <button type="submit" name="add_tariff" class="btn btn-success">Add Tariff</button>
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
         <script>
        // Get references to the select element and input fields
        const tariffTypeSelect = document.getElementById("tariff_type");
        const minKmInput = document.getElementById("min_km");
        const perKmRateInput = document.getElementById("per_km");
        const amount = document.getElementById('amount');

        tariffTypeSelect.addEventListener("change", function() {
            const selectedTariffType = tariffTypeSelect.value;

            if (selectedTariffType === "per_hour") {
                minKmInput.value = "100";
                perKmRateInput.value = "30";
                amount.value = 100*30;
            } 
            else if (selectedTariffType === "per_day") {
                minKmInput.value = "600";
                perKmRateInput.value = "30";
                amount.value = 600*30;
            } else if (selectedTariffType === "per_km") {
                minKmInput.value = "";
                perKmRateInput.value = "25";
                amount.value = "";
            }
            else {
                minKmInput.value = "";
                perKmRateInput.value = "";
                amount.value = "";
            }
        });
    </script>
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

