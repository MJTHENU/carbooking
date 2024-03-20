
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
                         <a href="#">User</a>
                     </li>
                     <li class="breadcrumb-item active">View Users</li>
                 </ol>
          
                 <!-- DataTables Example -->
                 <div class="card mb-3">
                     <div class="card-header">
                         <i class="fas fa-users"></i>
                         Registered Users
                     </div>
                     <div class="card-body">
                         <div class="table-responsive">
                             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                 <thead>
                                     <tr>
                                     <th scope="col">ID</th>
                                    <th scope="col"> Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Age</th>
                                    <!--<th scope="col">Date Of Birth</th>  -->
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">User Type</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Action</th>
                                     </tr>
                                 </thead>
                                 <?php   
                                   $sql = "select * from users";
                                   $run = mysqli_query($mysqli, $sql);

                                   $cnt = 1;
                                   
                                   while ($row= mysqli_fetch_array($run)) {

                                    $user_id = $row['user_id'];
                                    $first_name = $row['first_name'];
                                    $last_name = $row['last_name'];
                                    $email = $row['email'];
                                    $age = $row['age'];
                                    //$date_of_birth = $row['date_of_birth'];
                                    $mobile_no = $row['mobile_no'];
                                    $user_type = $row['user_type'];
                                    $gender = $row['gender'];
                                    $password = $row['password'];

                                   ?>
                       
                                 <tbody>
                                     <tr>
                                     <td><?php echo $cnt ?></td>
                                    <td><?php echo $first_name . " " . $last_name ?></td>
                                    <td><?php echo $email ?></td>
                                    <td><?php echo $age ?></td>
                                 <!--   <td><//?php echo $date_of_birth ?></td>   -->
                                    <td><?php echo $mobile_no ?></td>
                                    <td><?php echo $password ?></td>
                                    <td><?php echo $user_type ?></td>
                                    <td><?php echo $gender ?></td>
                                         <td>
                                             <a href="edit.php?user_id=<?php echo $user_id ?>" class="badge badge-success"><i class="fa fa-user-edit"></i> Update</a>
                                             <a href="delete.php?del=<?php echo $user_id ?>" class="badge badge-danger"><i class="fa fa-trash"></i> Delete</a>
                                         </td>
                                     </tr>
                                 </tbody>
                                 <?php $cnt = $cnt+1; }?>

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