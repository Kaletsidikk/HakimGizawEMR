<?php
// Start the session to retrieve session messages
session_start();

// Include database configuration
include('assets/inc/config.php');

// Get the appointment ID from the query parameter (this is where you are trying to approve the appointment)
$appointment_id = $_GET['id']; 

// Check if the appointment ID is valid
if (!isset($appointment_id) || empty($appointment_id)) {
    echo "Invalid appointment ID.";
    exit;
}

// SQL query to approve the appointment
$sql = "UPDATE his_appointments SET appointment_status = 'approved' WHERE appointment_id = ?";

// Prepare the SQL statement
$stmt = $mysqli->prepare($sql);

// Check if the statement was prepared correctly
if ($stmt === false) {
    // If the statement preparation fails, print an error message
    echo "Error in preparing statement: " . $mysqli->error;
    exit;
}

// Bind the appointment ID to the SQL query
$stmt->bind_param('i', $appointment_id);

// Execute the statement
if ($stmt->execute()) {
    // If the query is successful, redirect or display a success message
    $_SESSION['success'] = "Appointment approved successfully!";
    header('Location: view_appointments.php');
    exit;
} else {
    // If the execution fails, display the error
    echo "Error in executing query: " . $stmt->error;
}

// Close the statement and the connection
$stmt->close();
$mysqli->close();
?>
