<?php
session_start();
include('assets/inc/config.php');

// Check if the user is logged in
if (!isset($_SESSION['pat_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $doctor_name = $_POST['doctor_name'];
    $phone_number = $_POST['phone_number'];
    $reason = $_POST['reason'];
    $pat_id = $_SESSION['pat_id'];

    // Insert the appointment request into the database
    $query = "INSERT INTO appointments (appointment_date, appointment_time, doctor_name, phone_number, reason, patient_id) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die('Error preparing query: ' . $mysqli->error);
    }

    $stmt->bind_param("sssssi", $appointment_date, $appointment_time, $doctor_name, $phone_number, $reason, $pat_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment request submitted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: dashboard.php");
    exit();
}
?>
