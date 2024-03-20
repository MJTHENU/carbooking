<?php
session_start();
include('../vendor/inc/config.php');
include('../vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['admin_id'];

// Add User
if (isset($_GET['document_id'])) {
    $edit = $_GET['document_id'];

    // Retrieve the vehicle details from the database
    $sql = "SELECT * FROM vehicle_documents WHERE document_id = '$edit'";
    $run = mysqli_query($mysqli, $sql);

    if ($run && mysqli_num_rows($run) > 0) {
        $row = mysqli_fetch_assoc($run); // Fetch the data into $row

        $document_id = $row['document_id'];
        $vehicle_id = $row['vehicle_id'];
        $registration_certificate = $row['registration_certificate'];
        $book_img = $row['book_img'];
        $insurance_certificate = $row['insurance_certificate'];
        $fitness_certificate = $row['fitness_certificate'];
        $permit_certificate = $row['permit_certificate'];
        $insurance_exp_date = $row['insurance_exp_date'];
        $fitness_exp_date = $row['fitness_exp_date'];
        $permit_exp_date = $row['permit_exp_date'];
        $status = $row['status'];
    } else {
        echo "Vehicle_documents not found.";
        exit();
    }
} else {
    echo "Edit parameter not provided in the URL.";
    exit();
}

// Function to handle file upload and validation
function validateAndUploadImage($fieldName, &$error_messages)
{
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] == UPLOAD_ERR_OK) {
        $image_name = $_FILES[$fieldName]['name'];
        $temp_name = $_FILES[$fieldName]['tmp_name'];
        $image_type = $_FILES[$fieldName]['type'];

        // Check if the uploaded file is an image
        if (strpos($image_type, 'image') !== false) {
            // Read image data
            $image_data = file_get_contents($temp_name);

            // Encode image data as base64 with the correct prefix
            return 'data:image/jpeg;base64,' . base64_encode($image_data);
        } else {
            $error_messages[$fieldName] = 'Invalid file format. Please upload an image.';
            return null;
        }
    }
    return null;
}

// Check if the form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $error_messages = array();

    // Check if any file uploads are present
    if (
        $_FILES['registration_certificate']['size'] > 0 &&
        $_FILES['book_img']['size'] > 0 &&
        $_FILES['insurance_certificate']['size'] > 0 &&
        $_FILES['fitness_certificate']['size'] > 0 ||
        $_FILES['permit_certificate']['size'] > 0
    ) {
        // Process each file individually
        $registration_certificate = validateAndUploadImage('registration_certificate', $error_messages);
        $book_img = validateAndUploadImage('book_img', $error_messages);
        $insurance_certificate = validateAndUploadImage('insurance_certificate', $error_messages);
        $fitness_certificate = validateAndUploadImage('fitness_certificate', $error_messages);
        $permit_certificate = validateAndUploadImage('permit_certificate', $error_messages);

        // Build the SQL query based on uploaded files
        $sql = "UPDATE vehicle_documents SET 
            vehicle_id = '$vehicle_id',
            registration_certificate = '$registration_certificate',
            book_img = '$book_img',
            insurance_certificate = '$insurance_certificate',
            fitness_certificate = '$fitness_certificate',
            permit_certificate = '$permit_certificate',
            insurance_exp_date = '$insurance_exp_date',
            fitness_exp_date = '$fitness_exp_date',
            permit_exp_date = '$permit_exp_date',
            status = '$status'
            WHERE document_id = '$edit'";
    } else {
        echo "No files were provided for update";
        exit(); // Add exit to stop further execution
    } if ($_FILES['registration_certificate']['size'] > 0) {
        $registration_certificate = validateAndUploadImage('registration_certificate', $error_messages);
        $sql = "UPDATE vehicle_documents SET vehicle_id = '$vehicle_id', registration_certificate = '$registration_certificate' WHERE document_id = '$edit'";
    } if ($_FILES['book_img']['size'] > 0) {
        $book_img = validateAndUploadImage('book_img', $error_messages);
        $sql = "UPDATE vehicle_documents SET vehicle_id = '$vehicle_id', book_img = '$book_img' WHERE document_id = '$edit'";
    } if ($_FILES['insurance_certificate']['size'] > 0) {
        $insurance_certificate = validateAndUploadImage('insurance_certificate', $error_messages);
        $sql = "UPDATE vehicle_documents SET vehicle_id = '$vehicle_id', insurance_certificate = '$insurance_certificate' WHERE document_id = '$edit'";
    } if ($_FILES['fitness_certificate']['size'] > 0) {
        $fitness_certificate = validateAndUploadImage('fitness_certificate', $error_messages);
        $sql = "UPDATE vehicle_documents SET vehicle_id = '$vehicle_id', fitness_certificate = '$fitness_certificate' WHERE document_id = '$edit'";
    } if ($_FILES['permit_certificate']['size'] > 0) {
        $permit_certificate = validateAndUploadImage('permit_certificate', $error_messages);
        $sql = "UPDATE vehicle_documents SET vehicle_id = '$vehicle_id', permit_certificate = '$permit_certificate' WHERE document_id = '$edit'";
    } else {
        echo "Data not updated";
    }

    if ($mysqli->query($sql) === TRUE) {
        echo '<script>location.replace("index.php")</script>';
    } else {
        echo 'Error: ' . $sql . '<br>' . $mysqli->error;
    }

    $mysqli->close();
}

?>



<!DOCTYPE html>

<html lang="en">
<style>
    .error {
        color: #FF0000;
    }
</style>
<?php include('../vendor/inc/head.php'); ?>

<body id="page-top">
    <!--Start Navigation Bar-->
    <?php include("../vendor/inc/nav.php"); ?>
    <!--Navigation Bar-->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../vendor/inc/sidebar.php"); ?>
        <!--End Sidebar-->
        <div id="content-wrapper">

            <div class="container-fluid">
                <?php if (isset($succ)) { ?>
                    <!--This code for injecting an alert-->
                    <script>
                        setTimeout(function() {
                            swal("Success!", "<?php echo $succ; ?>!", "success");
                        }, 100);
                    </script>

                <?php } ?>
                <?php if (isset($err)) { ?>
                    <!--This code for injecting an alert-->
                    <script>
                        setTimeout(function() {
                            swal("Failed!", "<?php echo $err; ?>!", "Failed");
                        }, 100);
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

                        <form action="edit.php?document_id=<?php echo $document_id ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="vehicle_id">Vehicle ID:</label>
                                <input type="text" id="vehicle_id" class="form-control" name="vehicle_id" value="<?php echo $vehicle_id ?>">
                                <?php if (isset($error_messages['vehicle_id'])) echo "<span class='error'>* " . $error_messages['vehicle_id'] . "</span>"; ?>
                            </div>

                            <div class="form-group">
                                <label for="registration_certificate">Registration Certificate Image:</label>
                                <input type="file" id="registration_certificate" class="form-control" name="registration_certificate">
                                <?php if (isset($error_messages['registration_certificate'])) echo "<span class='error'>* " . $error_messages['registration_certificate'] . "</span>"; ?>
                                <?php if (!empty($registration_certificate)) : ?>
                                    <img src="<?php echo $registration_certificate; ?>" alt="registration_certificate" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="book_img">Book Image:</label>
                                <input type="file" id="book_img" class="form-control" name="book_img">
                                <?php if (isset($error_messages['book_img'])) echo "<span class='error'>* " . $error_messages['book_img'] . "</span>"; ?>
                                <?php if (!empty($book_img)) : ?>
                                    <img src="<?php echo $book_img; ?>" alt="book_img" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="insurance_certificate">Insurance Certificate Image:</label>
                                <input type="file" id="insurance_certificate" class="form-control" name="insurance_certificate">
                                <?php if (isset($error_messages['insurance_certificate'])) echo "<span class='error'>* " . $error_messages['insurance_certificate'] . "</span>"; ?>
                                <?php if (!empty($insurance_certificate)) : ?>
                                    <img src="<?php echo $insurance_certificate; ?>" alt="insurance_certificate" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="fitness_certificate">Fitness Certificate Image:</label>
                                <input type="file" id="fitness_certificate" class="form-control" name="fitness_certificate">
                                <?php if (isset($error_messages['fitness_certificate'])) echo "<span class='error'>* " . $error_messages['fitness_certificate'] . "</span>"; ?>
                                <?php if (!empty($fitness_certificate)) : ?>
                                    <img src="<?php echo $fitness_certificate; ?>" alt="fitness_certificate" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="permit_certificate">Permit Certificate Image:</label>
                                <input type="file" id="permit_certificate" class="form-control" name="permit_certificate">
                                <?php if (isset($error_messages['permit_certificate'])) echo "<span class='error'>* " . $error_messages['permit_certificate'] . "</span>"; ?>
                                <?php if (!empty($permit_certificate)) : ?>
                                    <img src="<?php echo $permit_certificate; ?>" alt="permit_certificate" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="insurance_exp_date">Insurance Expiration Date:</label>
                                <input type="date" id="insurance_exp_date" class="form-control" name="insurance_exp_date" value="<?php echo $insurance_exp_date ?>">
                            </div>

                            <div class="form-group">
                                <label for="fitness_exp_date">Fitness Expiration Date:</label>
                                <input type="date" id="fitness_exp_date" class="form-control" name="fitness_exp_date" value="<?php echo $fitness_exp_date ?>">
                            </div>

                            <div class="form-group">
                                <label for="permit_exp_date">Permit Expiration Date:</label>
                                <input type="date" id="permit_exp_date" class="form-control" name="permit_exp_date" value="<?php echo $permit_exp_date ?>">
                            </div>

                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select id="status" class="form-control" name="status">
                                    <option value="pending" <?php if ($status === 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="approved" <?php if ($status === 'approved') echo 'selected'; ?>>Approved</option>
                                </select>
                            </div>

                            <?php if (isset($error_messages['document_fields'])) echo "<span class='error'>* " . $error_messages['document_fields'] . "</span>"; ?>

                            <button type="submit" name="update" class="btn btn-success">Update Document</button>
                        </form>
                        <!-- End Form-->

                    </div>
                </div>

                <hr>
                <!-- Sticky Footer -->
                <?php include("../vendor/inc/footer.php"); ?>

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

         <script>
    function updateFileName(input) {
        var oldFileNameInput = document.getElementById('oldFileName');
        var hiddenInput = document.getElementsByName('old_filename')[0];

        if (input.files && input.files[0]) {
            // Display the selected file name
            oldFileNameInput.value = 'Old File: ' + input.files[0].name;
            
            // Attach old filename to the hidden input
            hiddenInput.value = input.files[0].name;
        }
    }
</script>


 </body>
 

 </html>
 

