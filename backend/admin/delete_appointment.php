<?php
// Include database configuration
include('assets/inc/config.php');
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    // Get the appointment ID from the URL
    $appointment_id = (int)$_GET['id'];

    // SQL query to delete the appointment
    $query = "DELETE FROM his_appointments WHERE appointment_id = ?";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($query)) {
        // Bind the appointment ID to the query
        $stmt->bind_param("i", $appointment_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['success'] = "Appointment deleted successfully!";
        } else {
            // Set error message
            $_SESSION['error'] = "Error: Could not delete the appointment.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error: Could not prepare the query.";
    }
} else {
    $_SESSION['error'] = "Error: No appointment ID provided.";
}

// Redirect back to view_appointments.php
header("Location: view_appointments.php");
exit();
?>
