<?php
// Start the session to retrieve session messages
session_start();

// Display success message if set
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']); // Clear the success message after displaying it
}

// Display error message if set
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // Clear the error message after displaying it
}

// Include database configuration
include('assets/inc/config.php');

// Fetch approved appointments from the database
$appointments = [];
$sql = "SELECT a.id, a.appointment_date, a.appointment_time, a.doctor_name, a.reason, p.pat_fname, p.pat_lname, p.pat_phone 
        FROM appointments a
        JOIN his_patients p ON a.patient_id = p.pat_id
        WHERE a.status = 'approved'
        ORDER BY a.appointment_date ASC, a.appointment_time ASC"; // Orders by date and time
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
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
                                <h4 class="page-title">View Approved Appointments</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Appointment Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Approved Appointments</h4>

                                    <!-- Table to display approved appointments -->
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>Phone Number</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Doctor Name</th>
                                                <th>Reason</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($appointments) > 0) : ?>
                                                <?php foreach ($appointments as $index => $appointment) : ?>
                                                    <tr>
                                                        <td><?php echo $index + 1; ?></td>
                                                        <td><?php echo $appointment['pat_fname'] . ' ' . $appointment['pat_lname']; ?></td>
                                                        <td><?php echo $appointment['pat_phone']; ?></td>
                                                        <td><?php echo $appointment['appointment_date']; ?></td>
                                                        <td><?php echo $appointment['appointment_time']; ?></td>
                                                        <td><?php echo $appointment['doctor_name']; ?></td>
                                                        <td><?php echo $appointment['reason']; ?></td>
                                                        <td>
                                                            <!-- View or other actions -->
                                                            <a href="view_appointment_details.php?id=<?php echo $appointment['id']; ?>" class="btn btn-info btn-sm">View</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No approved appointments found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
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
