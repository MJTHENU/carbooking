
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
                         <a href="#">Vehicle Tariff</a>
                     </li>
                     <li class="breadcrumb-item active">View Vehicle Tariff</li>
                 </ol>
          
                 <!-- DataTables Example -->
                 <div class="card mb-3">
                     <div class="card-header">
                         <i class="fas fa-users"></i>
                         Registered VehicleTariff
                     </div>
                     <div class="card-body">
                         <div class="table-responsive">
                             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                 <thead>
                                     <tr>
                                     <th scope="col">ID</th>
                                    <th scope="col">Vehicle ID</th>
                                    <th scope="col">Tariff ID</th>
                                    <th scope="col">Vehicle Name</th>
                                    <th scope="col">Model</th>
                                    <th scope="col">Tariff Name</th>
                                    <th scope="col">status</th>
                                     </tr>
                                 </thead>
                                 <?php   
                                   $sql = "select * from vehicle_tariff";
                                   $run = mysqli_query($mysqli, $sql);

                                   $id = 1;
                                   
                                   while ($row= mysqli_fetch_array($run)) {

                                    $vehicle_id = $row['vehicle_id'];
                                    $tariff_id = $row['tariff_id'];
                                    $vehicle_name = $row['vehicle_name'];
                                    $model = $row['model'];
                                    $tariff_name = $row['tariff_name'];
                                    $status = $row['status'];

                                   ?>
                                 <tbody>
                                     <tr>
                                     <td><?php echo $id ?></td>
                                    <td><?php echo $vehicle_id ?></td>
                                    <td><?php echo $tariff_id ?></td>
                                    <td><?php echo $vehicle_name ?></td>
                                    <td><?php echo $model ?></td>
                                    <td><?php echo $tariff_name ?></td>
                                    <td><?php echo $status ?></td>
                                     </tr>
                                 </tbody>
                                 <?php  $id++; }?>

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

     <script>
    // JavaScript function to hide the "Start Trip" button when clicked
    document.addEventListener('DOMContentLoaded', function () {
        const startTripButtons = document.querySelectorAll('.start-trip-button');

        startTripButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Hide the clicked button
                button.style.display = 'none';
            });
        });
    });
</script>
  
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