<!--Server Side Scripting To inject Login-->
<?php
  //session_start();
  include('vendor/inc/config.php');
  //include('vendor/inc/checklogin.php');
  //check_login();
  //$aid=$_SESSION['a_id'];
  //Add USer
  if(isset($_POST['add_user']))
    {

            $first_name=$_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            $mobile_no=$_POST['mobile_no'];
            $user_tybe=$_POST['user_type'];
            $query="INSERT into `users` (first_name, last_name, email, password, mobile_no, user_type) values(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param('sssssss', $first_name,  $last_name, $email, $password, $mobile_no, $user_tybe);
            
                if($stmt)
                {
                    $succ = "Account Created Proceed To Log In";
                }
                else 
                {
                    $err = "Please Try Again Later";
                }
            }
?>

<!--End Server Side Scriptiong-->
<!DOCTYPE html>
<html lang="en">

<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Tranport Management System, Saccos, Matwana Culture">
    <meta name="author" content="MartDevelopers ">

    <title>Transport Managemennt System Client- Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>


<body class="bg-dark">
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
    <div class="container">
     
        <div class="card card-register mx-auto mt-5">
            <div class="card-header">Create An Account With Us</div>
            <div class="card-body">
                <!--Start Form-->
                <form method="post">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-label-group">
                                    <input type="text" required class="form-control" id="exampleInputEmail1" name="first_name">
                                    <label for="firstName">First name</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-group">
                                    <input type="text" class="form-control" id="u_lname" name="last_name">
                                    <label for="u_lname">Last name</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-group">
                                    <input type="text" class="form-control" id="email" name="email">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" class="form-control" id="password" name="password">
                            <label for="password">Password</label>
                        </div>
                    </div>
                    <div class="form-group" style="display:none">
                        <div class="form-label-group">
                            <input type="text" class="form-control" id="exampleInputEmail1" value="User" name="user_type">
                            <label for="user_type">User Tybe</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="tel" class="form-control" name="mobile_no">
              <label for=" mobile_no">Phone</label>
                        </div>
                    </div>
                    
                    <button type="submit" name="add_user" class="btn btn-success" style = "display:flex; justify-content: center;" >Create Account</button>
                </form>
                <!--End FOrm-->
                <div class="text-center">
                    <a class="d-block small mt-3" href="index.php">Login Page</a>
                    <a class="d-block small" href="usr-forgot-pwd.php">Forgot Password?</a>
                </div>
     
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!--INject Sweet alert js-->
    <script src="vendor/js/swal.js"></script>

</body>

</html>