<?php
    session_start();
    include('vendor/inc/config.php'); // get configuration file

    if(isset($_POST['user_login'])) {
        $email = $_POST['email'];
        $password = md5($_POST['password']); // Hash the provided password

        $stmt = $mysqli->prepare("SELECT email, password, user_id FROM users WHERE email=? AND password=?");
        $stmt->bind_param('ss', $email, $password); // Bind the email and hashed password parameters
        $stmt->execute();
        
        $stmt->bind_result($resultEmail, $resultPassword, $user_id); // Bind the result variables
        
        $rs = $stmt->fetch();
        
        if($rs) { // if it's successful
            $_SESSION['user_id'] = $user_id; // assign session to user id
            $vehicleId = $_SESSION['vehicle_id'];
            header("location:confirm-book.php?vehicle_id=$vehicleId");
            $succ = "Login Successful";
        } else {
            $error = "Invalid Email or Password";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
   
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Online Car Booking System - Client Login</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Custom fonts for this template-->
    <link href="user/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="user/vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">
  
    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Client Login Panel</div>
            <div class="card-body">
                <!--INJECT SWEET ALERT-->
                <!--Trigger Sweet Alert-->
                <?php if(isset($succ)) {?>
                <!--This code for injecting an alert-->
                <script>
                setTimeout(function() {
                        swal("Success!", "<?php echo $succ;?>!", "success");
                    },
                    100);
                </script>

                <?php } ?>
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
                    <a class="d-block small mt-3" href="register.php">Register an Account</a>
                    <a class="d-block small" href="./forgot-password.php">Forgot Password?</a>
                    <a class="d-block small" href="index.php">Home</a>
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
