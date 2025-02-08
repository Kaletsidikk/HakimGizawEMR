<?php
session_start();
include('assets/inc/config.php');

// Display success or error messages if present
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}

// Fetch appointments that are pending or scheduled
$appointments = [];
$sql = "SELECT a.id AS appointment_id, 
               a.appointment_date, 
               a.reason AS notes, 
               p.pat_fname, 
               p.pat_lname, 
               p.pat_phone, 
               a.status
        FROM appointments a
        JOIN his_patients p ON a.patient_id = p.pat_id
        WHERE a.status = 'pending' -- Adjust this if you need to show other statuses like 'approved'
        ORDER BY a.appointment_date ASC";

$result = $mysqli->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
} else {
    echo "Error: " . $mysqli->error;
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
                                <h4 class="page-title">Manage Appointments</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Appointments Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Appointments</h4>
                                    <p class="sub-header">Manage and review all pending appointments below:</p>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>Phone</th>
                                                <th>Appointment Date</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($appointments)) : ?>
                                                <?php foreach ($appointments as $index => $appointment) : ?>
                                                    <tr>
                                                        <td><?php echo $index + 1; ?></td>
                                                        <td><?php echo htmlspecialchars($appointment['pat_fname'] . ' ' . $appointment['pat_lname']); ?></td>
                                                        <td><?php echo htmlspecialchars($appointment['pat_phone']); ?></td>
                                                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                                        <td>
                                                            <?php 
                                                            // Display status (pending, approved, rejected)
                                                            echo ucfirst($appointment['status']); 
                                                            ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($appointment['notes']); ?></td>
                                                        <td>
                                                            <a href="approve_appointment.php?id=<?php echo $appointment['appointment_id']; ?>" 
                                                                class="btn btn-success btn-sm">Approve</a>
                                                            <a href="reject_appointment.php?id=<?php echo $appointment['appointment_id']; ?>" 
                                                                class="btn btn-danger btn-sm">Reject</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No appointments found.</td>
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

    <!-- Scripts -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>  
