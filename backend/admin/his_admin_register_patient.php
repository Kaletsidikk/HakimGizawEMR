<?php
session_start();
include('assets/inc/config.php'); // Database connection

$error_message = "";

if (isset($_POST['add_patient'])) {
    // Retrieve and sanitize form inputs
    $pat_fname = $_POST['pat_fname'];
    $pat_lname = $_POST['pat_lname'];
    $pat_number = $_POST['pat_number'];
    $pat_phone = $_POST['pat_phone'];
    $pat_type = $_POST['pat_type'];
    $pat_addr = $_POST['pat_addr'];
    $pat_age = $_POST['pat_age'];
    $pat_dob = $_POST['pat_dob'];
    $pat_ailment = $_POST['pat_ailment'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert patient details
    $query = "INSERT INTO his_patients (pat_fname, pat_ailment, pat_lname, pat_age, pat_dob, pat_number, pat_phone, pat_type, pat_addr, username, password) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $mysqli->prepare($query)) {
        // Bind parameters for the query
        $stmt->bind_param('sssssssssss', $pat_fname, $pat_ailment, $pat_lname, $pat_age, $pat_dob, $pat_number, $pat_phone, $pat_type, $pat_addr, $username, $hashed_password);
        
        // Execute the query
        if ($stmt->execute()) {
            $success = "Patient Details Added Successfully. Username and password are set.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "Error: " . $mysqli->error;
    }
}
?>

<!-- Start HTML Output -->
<!DOCTYPE html>
<html lang="en">

<!-- Include Head Section -->
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- End Topbar -->

        <!-- Left Sidebar Start -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- End Left Sidebar -->

        <!-- Page Content Start -->
        <div class="content-page">
            <div class="content">

                <!-- Start Content -->
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">Add Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Patient Details</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Form Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>

                                    <!-- Add Patient Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">First Name</label>
                                                <input type="text" required="required" name="pat_fname" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                <input required="required" type="text" name="pat_lname" class="form-control" id="inputPassword4" placeholder="Patient's Last Name">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">Date Of Birth</label>
                                                <input type="text" required="required" name="pat_dob" class="form-control" id="inputEmail4" placeholder="DD/MM/YYYY">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Age</label>
                                                <input required="required" type="text" name="pat_age" class="form-control" id="inputPassword4" placeholder="Patient's Age">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputAddress" class="col-form-label">Address</label>
                                            <input required="required" type="text" class="form-control" name="pat_addr" id="inputAddress" placeholder="Patient's Address">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputCity" class="col-form-label">Mobile Number</label>
                                                <input required="required" type="text" name="pat_phone" class="form-control" id="inputCity">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputCity" class="col-form-label">Patient Ailment</label>
                                                <input required="required" type="text" name="pat_ailment" class="form-control" id="inputCity">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputState" class="col-form-label">Patient's Type</label>
                                                <select id="inputState" required="required" name="pat_type" class="form-control">
                                                    <option>Choose</option>
                                                    <option>InPatient</option>
                                                    <option>OutPatient</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2" style="display:none">
                                                <?php 
                                                    $length = 5;    
                                                    $patient_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                                                ?>
                                                <label for="inputZip" class="col-form-label">Patient Number</label>
                                                <input type="text" name="pat_number" value="<?php echo $patient_number;?>" class="form-control" id="inputZip">
                                            </div>
                                        </div>

                                        <!-- Username and Password Fields -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputState" class="col-form-label">Username</label>
                                                <input type="text" required="required" name="username" class="form-control" placeholder="Enter Username">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputState" class="col-form-label">Password</label>
                                                <input type="password" required="required" name="password" class="form-control" placeholder="Enter Password">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_patient" class="ladda-button btn btn-primary" data-style="expand-right">Add Patient</button>

                                    </form>
                                    <!-- End Patient Form -->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div>
                    <!-- End Row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- End Footer -->

        </div>

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

</body>

</html>
