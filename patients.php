<?php
include './config/connection.php';
include './common_service/common_functions.php';


$message = '';
if (isset($_POST['save_Patient'])) {

    $patientName = trim($_POST['patient_name']);
    $address = trim($_POST['address']);
    $patient_number = trim($_POST['patient_number']);
    
    $dateBirth = trim($_POST['date_of_birth']);
    $dateArr = explode("/", $dateBirth);
    
    $dateBirth = $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];

    $phoneNumber = trim($_POST['phone_number']);

    $patientName = ucwords(strtolower($patientName));
    $address = ucwords(strtolower($address));

    $gender = $_POST['gender'];
if ($patientName != '' && $address != '' && 
  $patient_number != '' && $dateBirth != '' && $phoneNumber != '' && $gender != '') {
      $query = "INSERT INTO `patients`(`patient_name`, 
    `address`, `patient_number`, `date_of_birth`, `phone_number`, `gender`)
VALUES('$patientName', '$address', '$patient_number', '$dateBirth',
'$phoneNumber', '$gender');";
try {

  $con->beginTransaction();

  $stmtPatient = $con->prepare($query);
  $stmtPatient->execute();

  $con->commit();

  $message = 'patient added successfully.';

} catch(PDOException $ex) {
  $con->rollback();

  echo $ex->getMessage();
  echo $ex->getTraceAsString();
  exit;
}
}
  header("Location:congratulation.php?goto_page=patients.php&message=$message");
  exit;
}



try {

  $query = "SELECT `id`, `patient_name`, `address`, `patient_number`, 
  date_format(`date_of_birth`, '%d %b %Y') as `date_of_birth`, 
  `phone_number`, `gender` 
  FROM `patients` WHERE `deleted_at` IS NULL 
  ORDER BY `patient_name` ASC;";
  

  $stmtPatient1 = $con->prepare($query);
  $stmtPatient1->execute();

} catch(PDOException $ex) {
  echo $ex->getMessage();
  echo $ex->getTraceAsString();
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>

 <?php include './config/data_tables_css.php';?>

  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <title>Patients</title>

</head>
<body class="hold-transition sidebar-mini dark-mode layout-fixed layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
<?php include './config/header.php';
include './config/sidebar.php';?>  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Patients</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-success rounded-0 shadow collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Add Patients</h3>
          
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Expand">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Patient Name</label>
                <input type="text" id="patient_name" name="patient_name" required="required"
                  class="form-control form-control-sm rounded-0"/>
              </div>
              
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Address</label> 
                <input type="text" id="address" name="address" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Patient Number</label>
                <input type="text" id="patient_number" name="patient_number" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Date of Birth</label>
                  <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                    <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" 
                      data-target="#date_of_birth" name="date_of_birth" 
                      data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Gender</label>
                <select class="form-control form-control-sm rounded-0" id="gender" name="gender">
                  <?php echo getGender();?>
                </select>
              </div>
            </div>

            <div class="clearfix">&nbsp;</div>

            <div class="row">
              <div class="col-lg-11 col-md-10 col-sm-10 xs-hidden">&nbsp;</div>
              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <button type="submit" id="save_Patient" name="save_Patient" 
                  class="btn btn-success btn-sm btn-flat btn-block">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

     <br/>

 <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-success rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Total Patients</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
              <table id="all_patients" 
              class="table table-striped dataTable table-bordered dtr-inline" 
               role="grid" aria-describedby="all_patients_info">
              
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Patient Name</th>
                    <th>Address</th>
                    <th>Patient Number</th>
                    <th>Date Of Birth</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th>Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  $count = 0;
                  while($row =$stmtPatient1->fetch(PDO::FETCH_ASSOC)){
                    $count++;
                  ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['patient_name'];?></td>
                    <td><?php echo $row['address'];?></td>
                    <td><?php echo $row['patient_number'];?></td>
                    <td><?php echo $row['date_of_birth'];?></td>
                    <td><?php echo $row['phone_number'];?></td>
                    <td><?php echo $row['gender'];?></td>
                    <td>
                      <a href="update_patient.php?id=<?php echo $row['id'];?>" class="btn btn-success btn-sm btn-flat">
                        <i class="fa fa-edit"></i>
                      </a>
                      <button class="btn btn-danger btn-sm btn-flat delete-patient" data-id="<?php echo $row['id']; ?>">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                   
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
            </div>
        </div>
     
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

   
    </section>
  </div>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->
<?php 
 include './config/footer.php';

  $message = '';
  if(isset($_GET['message'])) {
    $message = $_GET['message'];
  }
?>  
  <!-- /.control-sidebar -->


<?php include './config/site_js_links.php'; ?>
<?php include './config/data_tables_js.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  showMenuSelected("#mnu_patients", "#mi_patients");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
  $('#date_of_birth').datetimepicker({
        format: 'L'
    });
      
    
   $(function () {
    $("#all_patients").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');
    
  });

  $(document).on('click', '.delete-patient', function () {
    var patientId = $(this).data('id');
    
    Swal.fire({
      title: "Are you sure?",
      text: "This patient will be soft deleted!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "delete_patient.php",
          type: "POST",
          data: { id: patientId },
          dataType: "json",
          success: function(response) {
            console.log("AJAX success:", response);
            if (response.success) {
              Swal.fire("Deleted!", response.message, "success").then(() => {
                location.reload();
              });
            } else {
              Swal.fire("Error!", response.message, "error");
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX error:", xhr.responseText);
            Swal.fire("Error!", "Something went wrong.", "error");
          }
        });
      }
    });
});

   
</script>
</body>
</html>