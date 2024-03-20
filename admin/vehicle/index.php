

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
                         <a href="#">Vehicles</a>
                     </li>
                     <li class="breadcrumb-item active">View Vehicles</li>
                 </ol>
        
                 <!-- DataTables Example -->
                 <div class="card mb-3">
                     <div class="card-header">
                         <i class="fas fa-users"></i>
                         Registered vehicles
                     </div>
                     <div class="card-body">
                         <div class="table-responsive">
                             <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                 <thead>
                                     <tr>
                                     <th scope="col">Vehicle ID</th>
                                    <th scope="col">Vehicle Name</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Model</th>
                                    <th scope="col">Bags</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Seat</th>
                                    <th scope="col">AC</th>
                                    <th scope="col">Status</th>
                                     </tr>
                                 </thead>
                                 <?php   
                                   $sql = "select * from vehicles";
                                   $run = mysqli_query($mysqli, $sql);

                                   
                                   
                                   while ($row= mysqli_fetch_array($run)) {

                                    $vehicle_id = $row['vehicle_id'];
                                    $vehicle_name = $row['vehicle_name'];
                                    $company = $row['company'];
                                    $model = $row['model'];
                                    $bag = $row['bag'];
                                    $year = $row['years'];
                                    $seat = $row['seat'];
                                    $ac = $row['ac'];
                                    $status = $row['status'];

                                   ?>
                         
                                 <tbody>
                                     <tr>
                                     <td><?php echo $vehicle_id ?></td>
                                    <td><?php echo $vehicle_name ?></td>
                                    <td><?php echo $company ?></td>
                                    <td><?php echo $model ?></td>
                                    <td><?php echo $bag ?></td>
                                    <td><?php echo $year ?></td>
                                    <td><?php echo $seat ?></td>
                                    <td><?php echo $ac ?></td>
                                    <td><?php echo $status ?></td>

                                    
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
     <script src="../vendor/datatables/jquery.dataTables.js"></script>
     <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

     <!-- Custom scripts for all pages-->
     <script src="../js/sb-admin.min.js"></script>

     <!-- Demo scripts for this page-->
     <script src="../js/demo/datatables-demo.js"></script>

     <script src="../vendor/js/sb-admin.min.js"></script>
  
 </body>

 </html>