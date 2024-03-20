
<?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add USer
  if (isset($_GET['id'])) {
    $edit = $_GET['id'];
    
    // Retrieve the vehicle details from the database
    $sql = "SELECT * FROM vehicle_tariff WHERE id = '$edit'";
    $run = mysqli_query($mysqli, $sql);

    if ($run && mysqli_num_rows($run) > 0) {
        $row = mysqli_fetch_assoc($run);
        $vehicle_id = $row['vehicle_id'];
        $tariff_id = $row['tariff_id'];
        $vehicle_name = $row['vehicle_name'];
        $model = $row['model'];
        $tariff_name = $row['tariff_name'];
        $status = $row['status'];

    } else {
        echo "Vehicle Tariff not found.";
        exit();
    }
} else {
    echo "Edit parameter not provided in the URL.";
    exit();
}

// Check if the form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $vehicle_id = $_POST['vehicle_id'];
        $tariff_id = $_POST['tariff_id'];
        $vehicle_name = $_POST['vehicle_name'];
        $model = $_POST['model'];
        $tariff_name = $_POST['tariff_name'];
        $status = $_POST['status'];

    $errors = array();

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
        $sql = "UPDATE vehicle_tariff SET vehicle_id = '$vehicle_id', tariff_id = '$tariff_id', vehicle_name = '$vehicle_name', model = '$model', tariff_name = '$tariff_name', status = '$status' WHERE id = '$edit'";

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
    
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                         <a href="#">Vehicle Tariff</a>
                     </li>
                     <li class="breadcrumb-item active">Update Vehicle Tariff</li>
                 </ol>
                 <hr>
                 <div class="card">
                     <div class="card-header">
                         Update Vehicle Tariff
                     </div>
                     <div class="card-body">
                         <!--Add User Form-->
            
                         <form action="edit.php?id=<?php echo $edit; ?>" method="POST">
                            
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
                                    <label for="tariff_id">Tariff:</label>
                                    <select id="tariff_id" class="form-control" name="tariff_id">
                                        <option value="">Select Tariff ID</option>
                                        <?php
                                        // Query to fetch vehicle IDs from the vehicle table
                                        $tariffQuery = "SELECT tariff_id FROM tariff";
                                        $tariffResult = mysqli_query($mysqli, $tariffQuery);

                                        if ($tariffResult) {
                                            while ($row = mysqli_fetch_assoc($tariffResult)) {
                                                $tariffID = $row['tariff_id'];
                                                $selected = ($tariffID == $tariff_id) ? 'selected' : ''; // Check if it should be selected

                                                echo '<option value="' . $tariffID . '" ' . $selected . '>' . $tariffID . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php if (isset($errors['tariff_id'])) echo "<span class='error'>* " . $errors['tariff_id'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                <label for="vehicle_name">Vehicle Name:</label>
                                <input type="text" class="form-control" id="vehicle_name" name="vehicle_name" value="<?php echo $vehicle_name ?>" >
                                <?php if (isset($errors['vehicle_name'])) echo "<span class='error'>* " . $errors['vehicle_name'] . "</span>"; ?>
                            </div>

                            <div class="form-group">
                                <label for="model">Model:</label>
                                <input type="text" id="model" class="form-control" name="model" value="<?php echo $model ?>">
                                <?php if (isset($errors['model'])) echo "<span class='error'>* " . $errors['model'] . "</span>"; ?>
                            </div>

                            <div class="form-group">
                                <label for="tariff_name">Tariff Name:</label>
                                <input type="text" id="tariff_name" class="form-control" name="tariff_name" value="<?php echo $tariff_name ?>">
                                <?php if (isset($errors['tariff_name'])) echo "<span class='error'>* " . $errors['tariff_name'] . "</span>"; ?>
                            </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" name="status" class = "form-control" >
                                        <option value="active" <?php if ($status === 'active') echo 'selected'; ?>>Active</option>
                                        <option value="inactive" <?php if ($status === 'inactive') echo 'selected'; ?>>InActive</option>
                                        
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
         <script src="../vendor/js/script.js"></script>
         

 </body>

 </html>