<?php include('../connection.php');?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Payment</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include('../menu/header.php'); ?>

    <div class="dashboard-container">
        <?php include('../menu/sidemenu.php'); ?>
    <h2>Add Payment</h2>
    <form action="" method="POST">
        <label for="booking_id">Booking ID:</label>
        <input type="number" id="booking_id" name="booking_id" required><br><br>
        
        <label for="payment_date">Payment Date:</label>
        <input type="date" id="payment_date" name="payment_date" required><br><br>
        
        <label for="amount">Amount:</label>
        <input type="number" step="0.01" id="amount" name="amount" required><br><br>
        
        <label for="payment_method">Payment Method:</label>
        <input type="text" id="payment_method" name="payment_method" required><br><br>
        
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="failed">Failed</option>
        </select><br><br>
        
        <input type="submit" value="Add Payment">
    </form>
    </div>

<?php include('../menu/footer.php'); ?>
</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST['booking_id'];
    $payment_date = $_POST['payment_date'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $sql = "INSERT INTO Payments (booking_id, payment_date, amount, payment_method, status)
            VALUES ('$booking_id', '$payment_date', '$amount', '$payment_method', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Payment added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
