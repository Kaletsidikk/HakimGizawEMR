<?php
session_start();
include('assets/inc/config.php');

// Check if the appointment ID is set
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Prepare the query to approve the appointment
    $query = "UPDATE appointments SET status = 'approved' WHERE id = ?";

    if ($stmt = $mysqli->prepare($query)) {
        // Bind the appointment ID and execute the query
        $stmt->bind_param("i", $appointment_id);
        
        if ($stmt->execute()) {
            // Set a success message in the session for the appointment approval
            $_SESSION['success'] = "Appointment approved successfully!";

            // Fetch the patient's phone number for SMS notification
            $sql = "SELECT p.pat_phone, p.pat_fname, p.pat_lname, a.appointment_date
                    FROM appointments a
                    JOIN his_patients p ON a.patient_id = p.pat_id
                    WHERE a.id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $appointment_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $appointment = $result->fetch_assoc();

            if ($appointment) {
                $patient_phone = $appointment['pat_phone'];
                $patient_name = $appointment['pat_fname'] . ' ' . $appointment['pat_lname'];
                $appointment_date = $appointment['appointment_date'];

                // SMS notification logic
                $api_key = ''; // get your own Infobip API Key
                $base_url = ''; // Infobip Base URL
                $sender = 'HakimGHospital'; // Sender name

                $url = "$base_url/sms/2/text/advanced";
                $data = [
                    "messages" => [
                        [
                            "from" => $sender,
                            "destinations" => [
                                ["to" => "+$patient_phone"]
                            ],
                            "text" => "Dear $patient_name, Your appointment on $appointment_date has been approved."
                        ]
                    ]
                ];

                // Set up cURL for Infobip API
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: App $api_key",
                    "Content-Type: application/json"
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute the request and get the response
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                // Check if the SMS was sent successfully
                if ($response === false || $http_code != 200) {
                    $_SESSION['error'] = "Appointment approved, but failed to send the SMS notification.";
                } else {
                    $_SESSION['success'] .= " SMS notification sent successfully.";
                }

                curl_close($ch);
            }
        } else {
            // Set an error message in the session for failed approval
            $_SESSION['error'] = "Failed to approve the appointment. Please try again.";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Set an error message if the query preparation fails
        $_SESSION['error'] = "Error preparing the query: " . $mysqli->error;
    }
} else {
    // If no appointment ID is passed, set an error message
    $_SESSION['error'] = "Invalid appointment ID.";
}

// Redirect back to the view appointments page
header("Location: view_appointment.php");
exit();
?>
