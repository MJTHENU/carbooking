
<?php
  session_start();
  include('../vendor/inc/config.php');
  include('../vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['admin_id'];
  //Add USer
  if (isset($_GET['user_id'])) {
    $edit = $_GET['user_id'];
    
    // Retrieve the vehicle details from the database
    $sql = "SELECT * FROM users WHERE user_id = '$edit'";
    $run = mysqli_query($mysqli, $sql);

    if ($run && mysqli_num_rows($run) > 0) {
        $row = mysqli_fetch_assoc($run);
        $user_id = $row['user_id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $age = $row['age'];
        $date_of_birth = $row['date_of_birth'];
        $mobile_no = $row['mobile_no'];
        $user_type = $row['user_type'];
        $gender = $row['gender'];
        $password = $row['password'];
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Edit parameter not provided in the URL.";
    exit();
}

// Check if the form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $date_of_birth = $_POST['date_of_birth'];
    $mobile_no = $_POST['mobile_no'];
    $password = md5($_POST['password']);
    $user_type = $_POST['user_type'];

    $errors = array();

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
        // Update the database
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', age = '$age', date_of_birth = '$date_of_birth',
        mobile_no = '$mobile_no', password = '$password', user_type = '$user_type', gender = '$gender' WHERE user_id = '$edit'";

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
                         Add User
                     </div>
                     <div class="card-body">
                         <!--Add User Form-->
                        
                
                         <form action="edit.php?user_id=<?php echo $edit; ?>" method="POST">
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" class = "form-control" name="first_name" value="<?php echo $first_name ?>" >
                                    <?php if (isset($errors['first_name'])) echo "<span class='error'>* " . $errors['first_name'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">      
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" class = "form-control" name="last_name" value="<?php echo $last_name ?>" >
                                    <?php if (isset($errors['last_name'])) echo "<span class='error'>* " . $errors['last_name'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">      
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" class = "form-control" name="email" value="<?php echo $email ?>" >
                                    <?php if (isset($errors['email'])) echo "<span class='error'>* " . $errors['email'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                    <label for="age">Age:</label>
                                    <input type="number" id="age" class = "form-control" name="age" min="0" value="<?php echo $age ?>">
                                    <?php if (isset($errors['age'])) echo "<span class='error'>* " . $errors['age'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth:</label>
                                    <input type="date" id="date_of_birth" class = "form-control" name="date_of_birth" min="1985-01-01" max="2005-12-12" value="<?php echo $date_of_birth ?>">
                                    <?php if (isset($errors['date_of_birth'])) echo "<span class='error'>* " . $errors['date_of_birth'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">        
                                    <label for="mobile_no">Mobile No:</label>
                                    <input type="tel" id="mobile_no" class = "form-control" name="mobile_no" pattern="^[7-9][0-9]{9}$" value="<?php echo $mobile_no ?>" >
                                    <?php if (isset($errors['mobile_no'])) echo "<span class='error'>* " . $errors['mobile_no'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" class = "form-control" name="password" value="<?php echo $password ?>" >
                                    <?php if (isset($errors['password'])) echo "<span class='error'>* " . $errors['password'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="user_type">User Type:</label>
                                    <select id="user_type" class = "form-control" name="user_type">
                                        <option value="User" <?php if ($user_type === 'User') echo 'selected'; ?>>User</option>
                                        <option value="Driver" <?php if ($user_type === 'Driver') echo 'selected'; ?>>Driver</option>
                                        <option value="Admin" <?php if ($user_type === 'Admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                    <?php if (isset($errors['user_type'])) echo "<span class='error'>* " . $errors['user_type'] . "</span>"; ?>
                                </div> 
                                <div class="form-group">
                                    <label>Gender:</label>
                                    <input type="radio" id="male" name="gender" value="Male" <?php if ($gender === 'male') echo 'checked'; ?>>
                                    <label for="male">Male</label>

                                    <input type="radio" id="female" name="gender" value="Female" <?php if ($gender === 'female') echo 'checked'; ?>>
                                    <label for="female">Female</label>

                                    <input type="radio" id="other" name="gender" value="Other" <?php if ($gender === 'other') echo 'checked'; ?>>
                                    <label for="other">Other</label>
                                    <?php if (isset($errors['gender'])) echo "<span class='error'>* " . $errors['gender'] . "</span>"; ?>
                                </div>

                             <button type="submit" name="update" class="btn btn-success">Update User</button>
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

 </body>
 

 </html>