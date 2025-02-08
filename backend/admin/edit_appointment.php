<?php
// Include the database configuration
include('assets/inc/config.php');
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the appointment ID from the URL
if (isset($_GET['id'])) {
    $appointment_id = (int)$_GET['id'];

    // SQL query to fetch the appointment details
    $query = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.doctor_name, a.appointment_notes, p.pat_fname, p.pat_lname, p.pat_phone 
              FROM his_appointments a
              JOIN his_patients p ON a.patient_id = p.pat_id
              WHERE a.appointment_id = ?";
    
    // Prepare the query
    if ($stmt = $mysqli->prepare($query)) {
        // Bind the appointment ID to the query
        $stmt->bind_param("i", $appointment_id);

        // Execute the query
        $stmt->execute();

        // Store the result
        $stmt->store_result();

        // Check if we found the appointment
        if ($stmt->num_rows > 0) {
            // Bind the result to variables
            $stmt->bind_result($appointment_id, $appointment_date, $appointment_time, $doctor_name, $appointment_notes, $pat_fname, $pat_lname, $pat_phone);
            $stmt->fetch();
        } else {
            $_SESSION['error'] = "Appointment not found.";
            header("Location: view_appointments.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Error: Could not prepare query.";
        header("Location: view_appointments.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Error: No appointment ID provided.";
    header("Location: view_appointments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include("assets/inc/nav.php"); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Edit Appointment</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Appointment</h4>

                                    <!-- Display error or success messages -->
                                    <?php
                                    if (isset($_SESSION['error'])) {
                                        echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                                        unset($_SESSION['error']);
                                    }

                                    if (isset($_SESSION['success'])) {
                                        echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                                        unset($_SESSION['success']);
                                    }
                                    ?>

                                    <!-- Edit Form -->
                                    <form action="update_appointment.php" method="POST">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">

                                        <div class="form-group">
                                            <label for="patient_name">Patient Name</label>
                                            <input type="text" class="form-control" id="patient_name" value="<?php echo $pat_fname . ' ' . $pat_lname; ?>" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="appointment_date">Appointment Date</label>
                                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="<?php echo $appointment_date; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="appointment_time">Appointment Time</label>
                                            <input type="time" class="form-control" id="appointment_time" name="appointment_time" value="<?php echo $appointment_time; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="doctor_name">Doctor Name</label>
                                            <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="<?php echo $doctor_name; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="appointment_notes">Notes</label>
                                            <textarea class="form-control" id="appointment_notes" name="appointment_notes" rows="3" required><?php echo $appointment_notes; ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Appointment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('assets/inc/footer.php'); ?>
        </div>
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
