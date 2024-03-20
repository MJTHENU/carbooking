<?php
  if(isset($_POST["submit"]))
  {
    $selectedDeliveryAddress = isset($_POST['optionsadd']) ? $_POST['optionsadd'] : '';
    $selectedPaymentMethod = isset($_POST['optionspay']) ? $_POST['optionspay'] : '';
  require ('PHPMailer/PHPMailerAutoload.php');

  $pid=$_SESSION['pid'];
  $cal_img=$_SESSION['image_path'];
  $cal_body=$_SESSION['cal_body'];
  $quantity=$_SESSION['quantity'];
  $price=$_SESSION['price'];
  $totalPrice=$_SESSION['totalPrice'];
  $category=$_SESSION["category"];
  $filePaths=$_SESSION['filePaths'];
  $email=$_SESSION['email'];


  $mail = new PHPMailer;

  //$mail->SMTPDebug = 4;                               // Enable verbose debug output

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'testprintmysproject@gmail.com';                 // SMTP username puvanraina03@gmail.com
  $mail->Password = 'ozmj vwmt bjhb ifnq';                           // SMTP password ibhrzagzqjaxuitj
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 587;                                    // TCP port to connect to

  $mail->setFrom('testprintmysproject@gmail.com', 'soundarya');
  $mail->addAddress('testprintmysproject@gmail.com');     // Add a recipient
  //$mail->addAddress('ellen@example.com');               // Name is optional
  //$mail->addReplyTo('info@example.com', 'Information');
  //$mail->addCC('cc@example.com');
  //$mail->addBCC('bcc@example.com');

  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
  $mail->isHTML(true);                                  // Set email format to HTML

  $mail->Subject ='Order from ' .$email. ' is here';
  //$mail->Body    = 'Email:' .$email.'category:' .$category. 'cal_body:'.$cal_body. 'price:' .$price. 'quantity:' .$quantity. 'totalPrice:' .$totalPrice;
  $mail->Body = '<html><body>';
  $mail->Body = '<h2>Order Details</h2>';
  $mail->Body .= '<table border="1">';
  $mail->Body .= '<tr><th>product id</th><th>Email</th><th>Category</th><th>Image_Path</th><th>Cal Body</th><th>Price</th><th>Quantity</th><th>Total Price</th><th>Whatsapp Number</th><th>Delivery Address</th><th>Payment Method</th></tr>';
  $mail->Body .= '<tr>';
  $mail->Body .= '<td>' . $pid . '</td>';
  $mail->Body .= '<td>' . $email . '</td>';
  $mail->Body .= '<td>' . $category . '</td>';
  $mail->Body .= '<td>' . $cal_img . '</td>';
  $mail->Body .= '<td>' . $cal_body . '</td>';
  $mail->Body .= '<td>' . $price . '</td>';
  $mail->Body .= '<td>' . $quantity . '</td>';
  $mail->Body .= '<td>' . $totalPrice . '</td>';

  $mail->Body .= '<td>' . $whatsappno . '</td>';
  $mail->Body .= '<td>' . $selectedDeliveryAddress . '</td>';
  $mail->Body .= '<td>' . $selectedPaymentMethod . '</td>';

  // Unserialize the $snaps variable and loop through the file paths
  //$unserializedSnaps = unserialize($snaps);
  $mail->Body .= '</td>'; // Closing table data tag
  $mail->Body .= '</tr>';
  $mail->Body .= '</table>';
  $mail->Body .= '</body></html>';
  foreach ($filePaths as $filePath) {
    // Add each file as an attachment
    $mail->addAttachment($filePath);
  }
  //$mail->addAttachment($cal_img);
  //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  if(!$mail->send()) {
      echo 'Message could not be sent.';
    // echo 'Mailer Error: ' . $mail->ErrorInfo;
  
  } else {  
      echo 'Message has been sent';
  
      $stmt = $conn->prepare("INSERT INTO booking (pid, image_path, category, cal_body, price, email, quantity, totalprice, photos, whatsappno, deliveryadd, payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssdsssssss", $pid, $cal_img, $category, $cal_body, $price, $email, $quantity, $totalPrice, $_SESSION['snaps'], $whatsappno, $selectedDeliveryAddress, $selectedPaymentMethod);  
      $stmt->execute();
      
      
      if ($stmt) {
        $successMessage = "Your order is booked successfully.";
        echo '<script>alert("' . $successMessage . '");</script>';
        echo '<script>window.location.href="home.php";</script>';
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
    
    $stmt->close(); 
  }
  }
  ?>