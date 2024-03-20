<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  $aid=$_SESSION['user_id'];
?>
 <!DOCTYPE html>
 <html lang="en">
 <!--Head-->
 <?php include ('vendor/inc/head.php');?>
 <!--End Head-->

 <body id="page-top">
     <!--Navbar-->
     <?php include ('vendor/inc/nav.php');?>
     <!--End Navbar-->

     <div id="wrapper">

         <!-- Sidebar -->
         <?php include('vendor/inc/sidebar.php');?>
         <!--End Sidebar-->

         <div id="content-wrapper">

             <div class="container-fluid">
                 <!-- Breadcrumbs-->
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item">
                         <a href="user-dashboard.php">Dashboard</a>
                     </li>
                     <li class="breadcrumb-item">Vehicle</li>
                     <li class="breadcrumb-item active">Book Vehicle</li>

                 </ol>


                 <!--Bookings-->
                 <div class="card mb-3">
                     <div class="card-header">
                         <i class="fas fa-bus"></i>
                         Available Vehicles
                     </div>
                     <div class="card-body">
                         <div class="table-responsive">
                             <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                 <thead>
                                     <tr>
                                         <th>#</th>
                                         <th>Vehicle Name</th>
                                         <th>Model</th>
                                         <th>Seats</th>
                                         <th>company</th>
                                         <th>Action</th>
                                     </tr>
                                 </thead>

                                 <tbody>
                                     <?php
                   
                  $sql="SELECT * FROM vehicles where status ='Available'"; 
                  $run = mysqli_query($mysqli, $sql);
                  $cnt=1;
                  while ($row= mysqli_fetch_array($run)) {
                    $vehicle_name = $row['vehicle_name'];
                    $vehicle_id =$row['vehicle_id'];
                    $model =$row['model'];
                    $seat = $row['seat'];
                    $company = $row['company'];
                ?>
                                     <tr>
                                         <td><?php echo $cnt;?></td>
                                         <td><?php echo $vehicle_name;?></td>
                                         <td><?php echo $model;?></td>
                                         <td><?php echo $seat;?> Passengers</td>
                                         <td><?php echo $company;?></td>
                                         <td>
                                             <a href="confirm-vehicle.php?vehicle_id=<?php echo $vehicle_id;?>" class="btn btn-outline-success"><i class="fa fa-clipboard"></i> Book Vehicle</a>
                                         </td>
                                     </tr>
                                     <?php  $cnt++; }?>

                                 </tbody>
                             </table>
                         </div>
                     </div>
                     <div class="card-footer small text-muted">
                     <?php
                        date_default_timezone_set("Asia/Kolkata"); // India/Tamil Nadu timezone
                        echo "Generated At : " . date("h:i:sa");
                        ?>
                     </div>
                 </div>

             </div>
             <!-- /.container-fluid -->

             <!-- Sticky Footer -->
             <?php include("vendor/inc/footer.php");?>
   
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
                     <a class="btn btn-danger" href="user-logout.php">Logout</a>
                 </div>
             </div>
         </div>
     </div>
   
     <!-- Bootstrap core JavaScript-->
     <script src="vendor/jquery/jquery.min.js"></script>
     <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

     <!-- Core plugin JavaScript-->
     <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

     <!-- Page level plugin JavaScript-->
     <script src="vendor/chart.js/Chart.min.js"></script>
     <script src="vendor/datatables/jquery.dataTables.js"></script>
     <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

     <!-- Custom scripts for all pages-->
     <script src="vendor/js/sb-admin.min.js"></script>

     <!-- Demo scripts for this page-->
     <script src="vendor/js/demo/datatables-demo.js"></script>
     <script src="vendor/js/demo/chart-area-demo.js"></script>

 </body>

 </html>