<?php
// Start the session to retrieve session messages and doctor ID
session_start();

// Assuming the doctor is logged in, set the doctor id from the session
$doc_id = isset($_SESSION['doc_id']) ? $_SESSION['doc_id'] : 0;

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

// Fetch appointments from the database
$appointments = [];
$sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.doctor_name, a.appointment_notes, p.pat_fname, p.pat_lname, p.pat_phone 
        FROM his_appointments a
        JOIN his_patients p ON a.patient_id = p.pat_id
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
                                <h4 class="page-title">View Appointments</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Video Chat Button -->
                                    <div style="margin-bottom: 20px;">
                                        <button class="btn btn-primary" onclick="redirectToVideoChat()">Video Chat with Patient</button>
                                    </div>

                                    <h4 class="header-title">Appointments List</h4>

                                    <!-- Table to display appointments -->
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Name</th>
                                                <th>Phone Number</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Doctor Name</th>
                                                <th>Notes</th>
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
                                                        <td><?php echo $appointment['appointment_notes']; ?></td>
                                                        <td>
                                                            <!-- Edit and Delete buttons -->
                                                            <a href="edit_appointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                            <a href="delete_appointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No appointments found.</td>
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

    <!-- JavaScript Files -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    
    <!-- Inline JavaScript for Video Chat Redirection -->
    <script>
        function redirectToVideoChat() {
            // Replace 'localhost' with your server's IP address if needed.
            // Passing the doctor id as a query parameter.
            window.location.href = "https://meetup-production-40ff.up.railway.app/=<?php echo $doc_id; ?>";
        }
    </script>
</body>
</html>
