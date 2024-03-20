<?php
     include('vendor/inc/config.php'); 
require_once('../PHPMailer/PHPMailerAutoload.php'); // Make sure to provide the correct path to PHPMailerAutoload.php

if (isset($_POST['subforgot'])) {
    $login = $_REQUEST['login_var'];
    $query = "SELECT * FROM users WHERE email = '$login'";
    $res = mysqli_query($mysqli, $query);
    $count = mysqli_num_rows($res);
    

    if ($count == 1) {
        $findresult = mysqli_query($mysqli, "SELECT * FROM users WHERE email = '$login'");

        if ($res = mysqli_fetch_array($findresult)) {
            $oldftemail = $res['email'];
            $user_id = $res['user_id'];
        }

        $token = sprintf('%06d', rand(0, 999999));
        $inresult = mysqli_query($mysqli, "INSERT INTO pass_reset VALUES('', '$oldftemail', '$token')");

        if ($inresult) {
            $FromName = "Forgot password";
            $FromEmail = "sathishmass206@gmail.com";
            $ReplyTo = "sathishtom9655@gmail.com";

            // PHPMailer code
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'sathishTom9363@gmail.com'; // Your Gmail email address
                $mail->Password = 'dktj oaiz rgqc oxuv'; // Your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom($FromEmail, $FromName);
                $mail->addAddress($oldftemail);

                $mail->isHTML(true);
                $mail->Subject = 'You have received a password reset email';
                $mail->Body = "Your password reset token is: " . $token . ". This token will expire in 1 hours.";

  

                $mail->send();

                header("location:forgot-password.php?sent=1");
                $hide = '1';
            } catch (Exception $e) {
                header("location:forgot-password.php?servererr=1");
            }
        } else {
            header("location:forgot-password.php?something_wrong=1");
        }
    } else {
        header("location:forgot-password.php?err=" . $login);
    }
}
?>
