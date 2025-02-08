<?php
session_start();
include('assets/inc/config.php');

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    
    // Update status to rejected
    $query = "UPDATE appointments SET status = 'rejected' WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success'] = "Appointment rejected successfully.";
    } else {
        $_SESSION['error'] = "Failed to reject appointment.";
    }
} else {
    $_SESSION['error'] = "Invalid appointment ID.";
}

header("Location: view_appointment.php");
exit();
?>
