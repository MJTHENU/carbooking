
 <?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add Booking
  
$errors = array();

  if(isset($_POST['add_user']))
    {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $age = $_POST['age'];
        $date_of_birth = $_POST['date_of_birth'];
        $mobile_no = $_POST['mobile_no'];
        $password = md5($_POST['password']);
        $user_type = $_POST['user_type'];
       
    
        if (empty($first_name)) {
            $errors['first_name'] = "First Name is required";
        }
    
        elseif (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
            $errors['first_name'] = "First Name should contain only alphabetic characters";
        }
    
        if (!empty($last_name) && !preg_match('/^[A-Za-z]+$/', $last_name)) {
            $errors['last_name'] = 'Last Name should contain only letters.';
        }
    
        if (empty($email)) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        } else {
            
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $mysqli->query($sql);
        
            if ($result->num_rows > 0) {
                
                $errors['email'] = "Email address already exists. Please choose a different one.";
            }
        }
    
        if (!empty($age) && !is_numeric($age)) {
            $errors['age'] = "Age must be a number";
        }
    
        if (empty($mobile_no)) {
            $errors['mobile_no'] = "Phone Number is required";
        } elseif (!preg_match("/^[7-9][0-9]{9}$/", $mobile_no)) {
            $errors['mobile_no'] = "Invalid phone number format";
        } 
    
        if (empty($password)) {
            $errors['password'] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters long";
        }
    
        if (empty($user_type)) {
            $errors['user_type'] = "User Type is required";
        } elseif ($user_type !== "User" && $user_type !== "Driver" && $user_type !== "Admin") {
            $errors['user_type'] = "Invalid user type";
        }
    
        if (isset($_POST['gender'])) {
            $gender = $_POST['gender'];
        } else {
            $errors['gender'] = "Gender is required";
        }
    
        if (empty($errors)) {
            
            $sql = "INSERT INTO Users (first_name, last_name, email, age, date_of_birth, mobile_no, password, user_type, gender)
                    VALUES ('$first_name', '$last_name', '$email', '$age', '$date_of_birth', '$mobile_no', '$password', '$user_type', '$gender')";
    
            if ($mysqli->query($sql) === TRUE) {
               
                echo '<script>location.replace("../user/index.php")</script>';
    
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        } 
    }
    
    $mysqli->close();
?>
 
 <!DOCTYPE html>
 <html lang="en">
<head>


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
                         <a href="#">Bookings</a>
                     </li>
                     <li class="breadcrumb-item active">Add</li>
                 </ol>
                 <hr>
                 <div class="card">
                     <div class="card-header">
                         Add User
                     </div>
                     <div class="card-body">
                         <!--Add User Form-->         
                         <form method="POST">
                         <div class="form-group">
                                        <label for="first_name">First Name:</label>
                                        <input type="text" id="first_name" class = "form-control" name="first_name">
                                        <?php if (isset($errors['first_name'])) echo "<span class='error'>* " . $errors['first_name'] . "</span>"; ?>
                                </div>
                                <div class="form-group">      
                                        <label for="last_name">Last Name:</label>
                                        <input type="text" id="last_name" class = "form-control" name="last_name" >
                                        <?php if (isset($errors['last_name'])) echo "<span class='error'> " . $errors['last_name'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">      
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" class = "form-control" name="email" >
                                        <?php if (isset($errors['email'])) echo "<span class='error'>* " . $errors['email'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                        <label for="age">Age:</label>
                                        <input type="number" id="age" class = "form-control" name="age" min="0">
                                        <?php if (isset($errors['age'])) echo "<span class='error'>* " . $errors['age'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                        <label for="date_of_birth">Date of Birth:</label>
                                        <input type="date" id="date_of_birth" class = "form-control" name="date_of_birth" min="1985-01-01" max="2005-12-12">
                                        <?php if (isset($errors['date_of_birth'])) echo "<span class='error'>* " . $errors['date_of_birth'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">        
                                        <label for="mobile_no">mobile_no:</label>
                                        <input type="tel" id="mobile_no" class = "form-control" name="mobile_no" pattern="^[7-9][0-9]{9}$" >
                                        <?php if (isset($errors['mobile_no'])) echo "<span class='error'>* " . $errors['mobile_no'] . "</span>"; ?>
                                </div> 
                                <div class ="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" class = "form-control" id="password" name="password" >
                                        <?php if (isset($errors['password'])) echo "<span class='error'>* " . $errors['password'] . "</span>"; ?>
                                </div>
                                <div class="form-group">       
                                        <label for="user_type">User Type:</label>
                                        <select id="user_type" class = "form-control" name="user_type" >
                                            <option value="User">User</option>
                                            <option value="Driver">Driver</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                        <?php if (isset($errors['user_type'])) echo "<span class='error'>* " . $errors['user_type'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                        <label>Gender:</label>
                                        <input type="radio" id="male" name="gender" value="Male">
                                        <label for="male">Male</label>

                                        <input type="radio" id="female" name="gender" value="Female">
                                        <label for="female">Female</label>

                                        <input type="radio" id="other" name="gender" value="Other">
                                        <label for="other">Other</label>
                                        <?php if (isset($errors['gender'])) echo "<span class='error'>* " . $errors['gender'] . "</span>"; ?>
                                </div>

                             <button type="submit" name="add_user" class="btn btn-success">Submit</button>
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