<?php
session_start();
include('../vendor/inc/config.php');
include('../vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['admin_id'];
// Add User
$error_messages = array();

if (isset($_POST['add_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_name = $_POST['vehicle_name'];
    $company = $_POST['company'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $seat = $_POST['seat'];
    $ac = $_POST['ac'];

    // File upload handling
        if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] == UPLOAD_ERR_OK) {
            $image_name = $_FILES['vehicle_image']['name'];
            $temp_name = $_FILES['vehicle_image']['tmp_name'];
            $image_type = $_FILES['vehicle_image']['type'];

            // Check if the uploaded file is an image
            if (strpos($image_type, 'image') !== false) {
                // Read image data
                $image_data = file_get_contents($temp_name);

                // Encode image data as base64 with the correct prefix
                $base64_image = 'data:image/jpeg;base64,' . base64_encode($image_data);
            } else {
                $error_messages['vehicle_image'] = 'Invalid file format. Please upload an image.';
            }
        }


    // Validate form fields
    if (empty($vehicle_id)) {
        $error_messages['vehicle_id'] = 'Vehicle ID is required.';
    } elseif (strlen($vehicle_id) !== 10 || !ctype_alnum($vehicle_id)) {
        $error_messages['vehicle_id'] = 'Vehicle ID should contain exactly 10 alphanumeric characters without spaces.';
    } else {
        // Check if the "vehicle_id" already exists in the database
        $query = "SELECT vehicle_id FROM vehicles WHERE vehicle_id = '$vehicle_id'";
        $result = mysqli_query($mysqli, $query);

        if (mysqli_num_rows($result) > 0) {
            $error_messages['vehicle_id'] = 'Vehicle ID already exists.';
        }
    }

    if (empty($vehicle_name)) {
        $error_messages['vehicle_name'] = 'Vehicle Name is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9\s]+$/', $vehicle_name)) {
        $error_messages['vehicle_name'] = 'Vehicle Name should contain only letters, numbers, and spaces.';
    }

    if (!empty($company) && !preg_match('/^[a-zA-Z0-9\s]+$/', $company)) {
        $error_messages['company'] = 'Company should contain only letters, numbers, and spaces.';
    }

    if (!empty($model) && !preg_match('/^[a-zA-Z0-9\s]+$/', $model)) {
        $error_messages['model'] = 'Model should contain only letters, numbers, and spaces.';
    }

    if (!empty($year) && !preg_match('/^\d{4}$/', $year)) {
        $error_messages['year'] = 'Year should be a 4-digit number.';
    }

    if (empty($seat)) {
        $error_messages['seat'] = 'Seat is required.';
    } elseif (!is_numeric($seat) || $seat <= 0) {
        $error_messages['seat'] = 'Seat must be a valid number greater than 0.';
    }

    if (empty($ac) || ($ac !== 'yes' && $ac !== 'no')) {
        $error_messages['ac'] = 'Air Conditioning should be either "yes" or "no".';
    }

    if (empty($error_messages)) {
        // Insert data into the database
        if ($mysqli->connect_error) {
            die('Database connection failed: ' . $mysqli->connect_error);
        }

        $sql = "INSERT INTO vehicles (vehicle_id, vehicle_name, vehicle_img, company, model, years, seat, ac)
        VALUES ('$vehicle_id', '$vehicle_name', '$base64_image', '$company', '$model', '$year', $seat, '$ac')";

        if ($mysqli->query($sql) === TRUE) {
            echo '<script>location.replace("../vehicle/index.php")</script>';
        } else {
            echo 'Error: ' . $sql . '<br>' . $mysqli->error;
        }

        $mysqli->close();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
    <style>
        .error {color: #FF0000;}
    </style>
    <?php include('../vendor/inc/head.php');?>

    <body id="page-top">
        <!-- Start Navigation Bar -->
        <?php include("../vendor/inc/nav.php");?>
        <!-- Navigation Bar -->

        <div id="wrapper">
            <!-- Sidebar -->
            <?php include("../vendor/inc/sidebar.php");?>
            <!-- End Sidebar -->
            <div id="content-wrapper">
                <div class="container-fluid">
                    <?php if(isset($succ)) {?>
                    <!-- This code for injecting an alert -->
                    <script>
                    setTimeout(function() {
                            swal("Success!", "<?php echo $succ;?>!", "success");
                        },
                        100);
                    </script>
                    <?php } ?>
                    <?php if(isset($err)) {?>
                    <!-- This code for injecting an alert -->
                    <script>
                    setTimeout(function() {
                            swal("Failed!", "<?php echo $err;?>!", "Failed");
                        },
                        100);
                    </script>
                    <?php } ?>

                    <!-- Breadcrumbs -->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Drivers</a>
                        </li>
                        <li class="breadcrumb-item active">Add Driver</li>
                    </ol>
                    <hr>

                    <div class="card">
                        <div class="card-header">
                            Add Driver
                        </div>
                        <div class="card-body">
                            <!-- Add User Form -->
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="vehicle_name">Vehicle ID:</label>
                                    <input type="text" id="vehicle_id" class="form-control" name="vehicle_id">
                                    <?php if (isset($error_messages['vehicle_id'])) echo "<span class='error'>* " . $error_messages['vehicle_id'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="vehicle_name">Vehicle Name:</label>
                                    <input type="text" id="vehicle_name" class="form-control" name="vehicle_name">
                                    <?php if (isset($error_messages['vehicle_name'])) echo "<span class='error'>* " . $error_messages['vehicle_name'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="company">Company:</label>
                                    <input type="text" id="company" class="form-control" name="company">
                                    <?php if (isset($error_messages['company'])) echo "<span class='error'> " . $error_messages['company'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="model">Model:</label>
                                    <input type="text" id="model" class="form-control" name="model">
                                    <?php if (isset($error_messages['model'])) echo "<span class='error'> " . $error_messages['model'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="year">Year:</label>
                                    <input type="text" id="year" class="form-control" name="year">
                                    <?php if (isset($error_messages['year'])) echo "<span class='error'> " . $error_messages['year'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="seat">Seat:</label>
                                    <input type="number" id="seat" class="form-control" name="seat">
                                    <?php if (isset($error_messages['seat'])) echo "<span class='error'>* " . $error_messages['seat'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="ac">AC:</label>
                                    <select id="ac" class="form-control" name="ac">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <?php if (isset($error_messages['ac'])) echo "<span class='error'>* " . $error_messages['ac'] . "</span>"; ?>
                                </div>
                                <div class="form-group">
                                    <label for="vehicle_image">Vehicle Image:</label>
                                    <input type="file" id="vehicle_image" class="form-control" name="vehicle_image">
                                    <?php if (isset($error_messages['vehicle_image'])) echo "<span class='error'>* " . $error_messages['vehicle_image'] . "</span>"; ?>
                                </div>
                                <button type="submit" name="add_vehicle" class="btn btn-success">Add Vehicle</button>
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>

                    <hr>

                    <!-- Sticky Footer -->
                    <?php include("../vendor/inc/footer.php");?>
                </div>
                <!-- /.content-wrapper -->
            </div>
            <!-- /#wrapper -->

            <!-- Scroll to Top Button -->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Logout Modal -->
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
        </body>
    </html>
