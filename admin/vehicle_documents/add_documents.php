<?php
session_start();
include('../vendor/inc/config.php');
include('../vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['admin_id'];
$error_messages = array();

// Function to handle file upload and validation
function validateAndUploadImage($fieldName, &$error_messages) {
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

// Check if the form is submitted
if (isset($_POST['add_vehicle_document'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $registration_certificate = validateAndUploadImage('registration_certificate', $error_messages);
    $book_img = validateAndUploadImage('book_img', $error_messages);
    $insurance_certificate = validateAndUploadImage('insurance_certificate', $error_messages);
    $fitness_certificate = validateAndUploadImage('fitness_certificate', $error_messages);
    $permit_certificate = validateAndUploadImage('permit_certificate', $error_messages);
    $insurance_exp_date = $_POST['insurance_exp_date'];
    $fitness_exp_date = $_POST['fitness_exp_date'];
    $permit_exp_date = $_POST['permit_exp_date'];
    $status = $_POST['status'];

    $check_vehicle_query = "SELECT * FROM vehicles WHERE vehicle_id = '$vehicle_id'";
    $result = $mysqli->query($check_vehicle_query);

    if ($result->num_rows == 0) {
        $error_messages['vehicle_id'] = 'Invalid Vehicle ID. Please enter a valid Vehicle ID.';
    }

    // Validate form fields
    if (empty($vehicle_id) || empty($registration_certificate) || empty($book_img) || empty($insurance_certificate) || empty($fitness_certificate) || empty($permit_certificate) || empty($insurance_exp_date) || empty($fitness_exp_date) || empty($permit_exp_date) || empty($status)) {
        $error_messages['document_fields'] = 'All document fields are required.';
    }

    if (empty($error_messages)) {
        // Insert data into the database
        $document_query = "INSERT INTO vehicle_documents (vehicle_id, registration_certificate, book_img, insurance_certificate, fitness_certificate, permit_certificate, insurance_exp_date, fitness_exp_date, permit_exp_date, status)
        VALUES ('$vehicle_id', '$registration_certificate', '$book_img', '$insurance_certificate', '$fitness_certificate', '$permit_certificate', '$insurance_exp_date', '$fitness_exp_date', '$permit_exp_date', '$status')";

        if ($mysqli->query($document_query) !== TRUE) {
            echo 'Error: ' . $document_query . '<br>' . $mysqli->error;
        } else {
            echo '<script>location.replace("../vehicle_documents/index.php")</script>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include('../vendor/inc/head.php');?>
    <style>
        .error {color: #FF0000;}
    </style>


<body id="page-top">
    <?php include('../vendor/inc/nav.php');  ?>
    <div id="wrapper">
        <?php include('../vendor/inc/sidebar.php');  ?>
        <div id="content-wrapper">
            <div class="container-fluid">
                <!-- Your existing Breadcrumbs and card -->
                <div class="card">
                    <div class="card-header">
                        Add Vehicle Document
                    </div>
                    <div class="card-body">
                        <!-- Add Document Form -->
                        <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="vehicle_id">Vehicle ID:</label>
        <input type="text" id="vehicle_id" class="form-control" name="vehicle_id">
        <?php if (isset($error_messages['vehicle_id'])) echo "<span class='error'>* " . $error_messages['vehicle_id'] . "</span>"; ?>
    </div>

    <div class="form-group">
        <label for="registration_certificate">Registration Certificate Image:</label>
        <input type="file" id="registration_certificate" class="form-control" name="registration_certificate">
        <?php if (isset($error_messages['registration_certificate'])) echo "<span class='error'>* " . $error_messages['registration_certificate'] . "</span>"; ?>
    </div>

    <div class="form-group">
        <label for="book_img">Book Image:</label>
        <input type="file" id="book_img" class="form-control" name="book_img">
        <?php if (isset($error_messages['book_img'])) echo "<span class='error'>* " . $error_messages['book_img'] . "</span>"; ?>
    </div>

    <div class="form-group">
        <label for="insurance_certificate">Insurance Certificate Image:</label>
        <input type="file" id="insurance_certificate" class="form-control" name="insurance_certificate">
        <?php if (isset($error_messages['insurance_certificate'])) echo "<span class='error'>* " . $error_messages['insurance_certificate'] . "</span>"; ?>
    </div>

    <div class="form-group">
        <label for="fitness_certificate">Fitness Certificate Image:</label>
        <input type="file" id="fitness_certificate" class="form-control" name="fitness_certificate">
        <?php if (isset($error_messages['fitness_certificate'])) echo "<span class='error'>* " . $error_messages['fitness_certificate'] . "</span>"; ?>
    </div>

    <div class="form-group">
        <label for="permit_certificate">Permit Certificate Image:</label>
        <input type="file" id="permit_certificate" class="form-control" name="permit_certificate">
        <?php if (isset($error_messages['permit_certificate'])) echo "<span class='error'>* " . $error_messages['permit_certificate'] . "</span>"; ?>
    </div>

    <div class="form-group">
        <label for="insurance_exp_date">Insurance Expiration Date:</label>
        <input type="date" id="insurance_exp_date" class="form-control" name="insurance_exp_date">
    </div>

    <div class="form-group">
        <label for="fitness_exp_date">Fitness Expiration Date:</label>
        <input type="date" id="fitness_exp_date" class="form-control" name="fitness_exp_date">
    </div>

    <div class="form-group">
        <label for="permit_exp_date">Permit Expiration Date:</label>
        <input type="date" id="permit_exp_date" class="form-control" name="permit_exp_date">
    </div>

    <div class="form-group">
        <label for="status">Status:</label>
        <select id="status" class="form-control" name="status">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
        </select>
    </div>

    <?php if (isset($error_messages['document_fields'])) echo "<span class='error'>* " . $error_messages['document_fields'] . "</span>"; ?>

    <button type="submit" name="add_vehicle_document" class="btn btn-success">Add Document</button>
</form>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <a class="btn btn-danger" href="../logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

     <!-- Bootstrap core JavaScript -->
     <script src="../vendor/jquery/jquery.min.js"></script>
            <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript -->
            <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Page level plugin JavaScript -->
            <script src="../vendor/chart.js/Chart.min.js"></script>
            <script src="../vendor/datatables/jquery.dataTables.js"></script>
            <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

            <!-- Custom scripts for all pages -->
            <script src="../vendor/js/sb-admin.min.js"></script>

            <!-- Demo scripts for this page -->
            <script src="../vendor/js/demo/datatables-demo.js"></script>
            <script src="../vendor/js/demo/chart-area-demo.js"></script>
            <!-- Inject Sweet alert js -->
            <script src="../vendor/js/swal.js"></script>
            
    <?php include("../vendor/inc/footer.php");?>
</body>
</html>
