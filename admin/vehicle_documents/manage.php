

<?php
    session_start();
    include('../vendor/inc/config.php');
    include('../vendor/inc/checklogin.php');
    check_login();
    $aid=$_SESSION['admin_id'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    
    <?php include('../vendor/inc/head.php');?>

    <body id="page-top">

        <?php include("../vendor/inc/nav.php");?>

    
        <div id="wrapper">

            <!-- Sidebar -->
            <?php include('../vendor/inc/sidebar.php');?>

            <div id="content-wrapper">

                <div class="container-fluid">
                
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Vehicle Documents</a>
                        </li>
                        <li class="breadcrumb-item active">View Documents</li>
                    </ol>
            
                    <!-- DataTables Example -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-users"></i>
                            Registered Documents
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th scope="col">Document ID</th>
                                        <th scope="col">Vehicle ID</th>
                                        <th scope="col">Registration Certificate</th>
                                        <th scope="col">RC Book</th>
                                        <th scope="col">Insurance Certificate</th>
                                        <th scope="col">Fitness Certificate</th>
                                        <th scope="col">Permit Certificate</th>
                                        <th scope="col">Insurance Exp</th>
                                        <th scope="col">Fitness Exp</th>
                                        <th scope="col">Permit Exp</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <?php   
                                    $sql = "select * from vehicle_documents";
                                    $run = mysqli_query($mysqli, $sql);

                                    
                                    
                                    while ($row= mysqli_fetch_array($run)) {

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

                                    ?>
                            
                                    <tbody>
                                        <tr>
                                        <td><?php echo $row['document_id']; ?></td>
                                        <td><?php echo $row['vehicle_id']; ?></td>
                                        <td><img src="<?php echo $row['registration_certificate']; ?>" alt="Registration Certificate" width="100" height="100"></td>
                                        <td><img src="<?php echo $row['book_img']; ?>" alt="RC Book" width="100" height="100"></td>
                                        <td><img src="<?php echo $row['insurance_certificate']; ?>" alt="Insurance Certificate" width="100" height="100"></td>
                                        <td><img src="<?php echo $row['fitness_certificate']; ?>" alt="Fitness Certificate" width="100" height="100"></td>
                                        <td><img src="<?php echo $row['permit_certificate']; ?>" alt="Permit Certificate" width="100" height="100"></td>
                                        <td><?php echo $row['insurance_exp_date']; ?></td>
                                        <td><?php echo $row['fitness_exp_date']; ?></td>
                                        <td><?php echo $row['permit_exp_date']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td>
                                             <a href="edit.php?document_id=<?php echo $document_id ?>" class="badge badge-success"><i class="fa fa-user-edit"></i> Update</a>
                                             <a href="delete.php?del=<?php echo $document_id ?>" class="badge badge-danger"><i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                        </tr>
                                    </tbody>
                                    <?php  } ?>
                                    

                                </table>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                    </div>
                </div>
                <!-- /.container-fluid -->

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
                        <a class="btn btn-danger" href="../logout.php">Logout</a>
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
        <script src="../vendor/datatables/jquery.dataTables.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="../js/sb-admin.min.js"></script>

        <!-- Demo scripts for this page-->
        <script src="../js/demo/datatables-demo.js"></script>

        <script src="../vendor/js/sb-admin.min.js"></script>
    
    </body>

    </html>