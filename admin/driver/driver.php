
<?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add USer
  $errors = array();

  if(isset($_POST['add_driver']))
    {

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $pincode = $_POST['pincode'];
        $license_no = $_POST['license_no'];
        $mobile_no = $_POST['mobile_no'];
        $alternate_no = $_POST['alternate_no'];
        $status = $_POST['status'];
    
        if (empty($first_name)) {
            $errors['first_name'] = 'First Name is required.';
        } elseif (!preg_match('/^[A-Za-z\s]+$/', $first_name)) {
            $errors['first_name'] = 'First Name should contain only letters and spaces.';
        }
    
        if (empty($last_name)) {
            $errors['last_name'] = 'Last Name is required.';
        } elseif (!preg_match('/^[A-Za-z\s]+$/', $last_name)) {
            $errors['last_name'] = 'Last Name should contain only letters and spaces.';
        }
        
    
        if (empty($address)) {
            $errors['address'] = 'Address is required.';
        }
    
        if (empty($city)) {
            $errors['city'] = 'City is required.';
        }
    
        if (empty($pincode)) {
            $errors['pincode'] = 'Pincode is required.';
        } elseif (!is_numeric($pincode)) {
            $errors['pincode'] = 'Pincode should be a numeric value.';
        }
    
        if (empty($license_no)) {
            $errors['license_no'] = 'License Number is required.';
        } elseif (!preg_match('/^[A-Za-z0-9\s]+$/', $license_no)) {
            $errors['license_no'] = 'License Number should contain only letters, numbers, and spaces.';
        } else {
            // Check if the license number already exists in the database
            $checkQuery = "SELECT license_no FROM drivers WHERE license_no = '$license_no'";
            $checkResult = $mysqli->query($checkQuery);
        
            if ($checkResult && $checkResult->num_rows > 0) {
                $errors['license_no'] = 'License Number already exists.';
            }
        }
        
    
        if (empty($mobile_no)) {
            $errors['mobile_no'] = 'Mobile Number is required.';
        } elseif (!preg_match('/^\d{10}$/', $mobile_no)) {
            $errors['mobile_no'] = 'Mobile Number should be a 10-digit number.';
        }
    
        if (!empty($alternate_no) && !preg_match('/^\d{10}$/', $alternate_no)) {
            $errors['alternate_no'] = 'Alternate Number should be a 10-digit number if provided.';
        }
    
        if (empty($status)) {
            $errors['status'] = 'Status is required.';
        } elseif ($status !== 'Active' && $status !== 'Inactive') {
            $errors['status'] = 'Invalid Status value.';
        }
        
    
        if (empty($errors)) {
            
            $sql = "INSERT INTO drivers ( first_name, last_name, address, city, pincode, license_no, mobile_no, alternate_no, status)
                    VALUES ( '$first_name', '$last_name', '$address', '$city', '$pincode', '$license_no', '$mobile_no', '$alternate_no', '$status')";
    
            if ($mysqli->query($sql) === TRUE) {
                echo '<script>location.replace("../driver/index.php");</script>';
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
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" class = "form-control" name="first_name" >
                                    <?php if (isset($errors['first_name'])) echo "<span class='error'>* " . $errors['first_name'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" class = "form-control" name="last_name" >
                                    <?php if (isset($errors['last_name'])) echo "<span class='error'>* " . $errors['last_name'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                   <label for="address">Address:</label>
                                    <input type="text" id="address" class = "form-control" name="address">
                                    <?php if (isset($errors['address'])) echo "<span class='error'>* " . $errors['address'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" id="city" class = "form-control" name="city" >
                                    <?php if (isset($errors['city'])) echo "<span class='error'>* " . $errors['city'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="pincode">Pincode:</label>
                                    <input type="number" id="pincode" class = "form-control" name="pincode" >
                                    <?php if (isset($errors['pincode'])) echo "<span class='error'>* " . $errors['pincode'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="license_no">License Number:</label>
                                    <input type="text" id="license_no" class = "form-control" name="license_no" >
                                    <?php if (isset($errors['license_no'])) echo "<span class='error'>* " . $errors['license_no'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="mobile_no">Mobile Number:</label>
                                    <input type="text" id="mobile_no" class = "form-control" name="mobile_no" >
                                    <?php if (isset($errors['mobile_no'])) echo "<span class='error'>* " . $errors['mobile_no'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="alternate_no">Alternate Number:</label>
                                    <input type="text" id="alternate_no" class = "form-control" name="alternate_no">
                                    <?php if (isset($errors['alternate_no'])) echo "<span class='error'>* " . $errors['alternate_no'] . "</span>"; ?>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" name="status" class = "form-control">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <?php if (isset($errors['status'])) echo "<span class='error'>* " . $errors['status'] . "</span>"; ?><br><br>
                                </div>

                             <button type="submit" name="add_driver" class="btn btn-success">Add Driver</button>
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

