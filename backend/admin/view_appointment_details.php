<?php
// Start the session to retrieve session messages
session_start();

// Include database configuration
include('assets/inc/config.php');

// Check if an appointment ID is passed via the URL
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Fetch the appointment details from the database
    $sql = "SELECT a.id, a.appointment_date, a.appointment_time, a.doctor_name, a.reason, 
                   p.pat_fname, p.pat_lname, p.pat_phone 
            FROM appointments a
            JOIN his_patients p ON a.patient_id = p.pat_id
            WHERE a.id = ?"; // Use prepared statements to prevent SQL injection

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the appointment details
            $appointment = $result->fetch_assoc();
        } else {
            $_SESSION['error'] = "Appointment not found.";
            header("Location: view_appointments.php"); // Redirect back if no appointment found
            exit();
        }
    } else {
        $_SESSION['error'] = "Error fetching appointment details.";
        header("Location: view_appointments.php"); // Redirect back if query fails
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid appointment ID.";
    header("Location: view_appointments.php"); // Redirect back if no ID is passed
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
                                <h4 class="page-title">Appointment Details</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Appointment Information</h4>

                                    <!-- Display the appointment details -->
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Patient Name</th>
                                            <td><?php echo $appointment['pat_fname'] . ' ' . $appointment['pat_lname']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td><?php echo $appointment['pat_phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Appointment Date</th>
                                            <td><?php echo $appointment['appointment_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Appointment Time</th>
                                            <td><?php echo $appointment['appointment_time']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Doctor Name</th>
                                            <td><?php echo $appointment['doctor_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Reason</th>
                                            <td><?php echo $appointment['reason']; ?></td>
                                        </tr>
                                    </table>

                                    <!-- Back Button -->
                                    <a href="approved_appointments.php" class="btn btn-primary">Back to Appointments List</a>
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
