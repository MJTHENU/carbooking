<?php
    session_start();
    include('vendor/inc/config.php');//get configuration file
    if(isset($_POST['user_login']))
    {
      $email=$_POST['email'];
      $password=md5($_POST['password']);//
      $stmt = $mysqli->prepare("SELECT email, password, user_id FROM users WHERE email=? AND password=?");
      $stmt->bind_param('ss', $email, $password); // Bind the email and password parameters
      $stmt->execute();
      
      $stmt->bind_result($resultEmail, $resultPassword, $user_id); // Bind the result variables
      
      $rs = $stmt->fetch();
      $_SESSION['user_id']=$user_id;//assaign session to admin id
      //$uip=$_SERVER['REMOTE_ADDR'];
      //$ldate=date('d/m/Y h:i:s', time());
      if($rs)
      {//if its sucessfull
        header("location:user-dashboard.php");
      }

      else
      {
      #echo "<script>alert('Access Denied Please Check Your Credentials');</script>";
      $error = "User Name & Password Not Match";
      }
  }
?>

<!--End Server Side Script Injection-->
<!DOCTYPE html>
<html lang="en">

<head>
   
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Online Car Booking System - Client Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">
  
    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Client Login Panel</div>
            <div class="card-body">
                <!--INJECT SWEET ALERT-->
                <!--Trigger Sweet Alert-->
                <?php if(isset($error)) {?>
                <!--This code for injecting an alert-->
                <script>
                setTimeout(function() {
                        swal("Failed!", "<?php echo $error;?>!", "error");
                    },
                    100);
                </script>

                <?php } ?>
                <form method="POST">
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" name="email" id="inputEmail" class="form-control" required="required" autofocus="autofocus">
                            <label for="inputEmail">Email address</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" name="password" id="inputPassword" class="form-control" required="required">
                            <label for="inputPassword">Password</label>
                        </div>
                    </div>
                    <input type="submit" name="user_login" class="btn btn-success btn-block" value="Login">
                </form>
                <div class="text-center">
                    <a class="d-block small mt-3" href="user-register.php">Register an Account</a>
                    <a class="d-block small" href="usr-forgot-password.php">Forgot Password?</a>
                    <a class="d-block small" href="../index.php">Home</a>
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
