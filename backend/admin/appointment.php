<?php
// assets/inc/config.php: Include database connection
include('assets/inc/config.php');
session_start();

// Check if the admin is logged in (session check)
if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");  // Redirect if not logged in
    exit();
}

// Handle the form submission
if (isset($_POST['add_appointment'])) {
    // Get the data from the form
    $patient_id = $_POST['patient_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $doctor_name = $_POST['doctor_name'];
    $appointment_notes = $_POST['appointment_notes'];
    $patient_phone = $_POST['patient_phone']; // Manually entered phone number

    // Insert the appointment into the database
    $query = "INSERT INTO his_appointments (patient_id, appointment_date, appointment_time, doctor_name, appointment_notes) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('issss', $patient_id, $appointment_date, $appointment_time, $doctor_name, $appointment_notes);
    $stmt->execute();

    if ($stmt) {
        $success_db = " Appointment Scheduled Successfully in the Database!";  // Success message for DB insertion

        // Send SMS Reminder (Infobip API)
        $message = "ሀኪም ግዛው ሆስፒታል                                                   ጤና ይስጥልኝ! ውድ ደንበኛችን ቀጠሮዎ በ $appointment_date ቀን በ$appointment_time ከዶክተር $doctor_name ጋር ተይዟል። እባክዎ በቀጠሮዎ ሰዓት ይገኙ።  ";
        $sms_sent = sendSMSReminder($patient_phone, $message);  // Call the function to send SMS

        if ($sms_sent) {
            $success_sms = "SMS Reminder Sent Successfully!";  // Success message for SMS
        } else {
            $error_sms = "Error Sending SMS Reminder. Please check API response.";  // Error message for SMS
        }

    } else {
        $error_db = "Error scheduling the appointment in the Database.";  // Error message for DB
    }
}

// Function to send SMS reminder (Infobip API)
function sendSMSReminder($phone_number, $message) {
   
    $api_key = 'b507af0921ea9f330a0ea1c67b2f3e2c-a258ad4d-0e8f-43a3-ad63-13aa49d274ee'; // Infobip API Key
    $base_url = '2mjxx6.api.infobip.com'; // Correct Infobip base URL
    $sender = 'HakimGHospital'; // Your sender name (configured in Infobip)

    // Correct API endpoint (full URL)
    $url = "$base_url/sms/2/text/advanced";

    // Prepare the payload for the API request
    $data = [
        "messages" => [
            [
                "from" => $sender,
                "destinations" => [
                    ["to" => $phone_number] // Add the phone number here
                ],
                "text" => $message // The message to send
            ]
        ]
    ];

    // Initialize cURL request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: App $api_key", // API key for authorization
        "Content-Type: application/json" // Content type
    ]);
    curl_setopt($ch, CURLOPT_POST, 1); // Set request type to POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Pass the payload as JSON
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

    // Execute the cURL request and get the response
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP response code
    curl_close($ch); // Close the cURL session

    // Log the API response and HTTP status for debugging purposes
    error_log("API Response: $response");
    error_log("HTTP Status: $http_status");

    // If the API call was successful (status code 200), return true
    if ($http_status == 200) {
        return true;
    } else {
        // Log error if status code is not 200
        error_log("API call failed with status: $http_status. Response: $response");
        return false;
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
                                <h4 class="page-title">Schedule Appointment</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Appointment Details</h4>
                                    <form method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="patient_id" class="col-form-label">Patient ID</label>
                                                <select name="patient_id" id="patient_id" class="form-control" required>
                                                    <option value="">Select Patient</option>
                                                    <?php
                                                    // Fetch patients from database
                                                    $result = $mysqli->query("SELECT pat_id, CONCAT(pat_fname, ' ', pat_lname) AS patient_name FROM his_patients");
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='{$row['pat_id']}'>{$row['patient_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="patient_phone" class="col-form-label">Patient Phone</label>
                                                <input type="text" name="patient_phone" class="form-control" id="patient_phone" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="appointment_date" class="col-form-label">Appointment Date</label>
                                                <input type="date" name="appointment_date" class="form-control" id="appointment_date" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="appointment_time" class="col-form-label">Appointment Time</label>
                                                <input type="time" name="appointment_time" class="form-control" id="appointment_time" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="doctor_name" class="col-form-label">Doctor Name</label>
                                                <input type="text" name="doctor_name" class="form-control" id="doctor_name" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="appointment_notes" class="col-form-label">Appointment Notes</label>
                                            <textarea name="appointment_notes" class="form-control" id="appointment_notes" rows="3" required></textarea>
                                        </div>

                                        <button type="submit" name="add_appointment" class="btn btn-primary">Schedule Appointment</button>
                                    </form>

                                    <!-- Display success or error messages -->
                                    <?php if (isset($success_db)) { echo "<p style='color: green;'>$success_db</p>"; } ?>
                                    <?php if (isset($success_sms)) { echo "<p style='color: green;'>$success_sms</p>"; } ?>
                                    <?php if (isset($error_db)) { echo "<p style='color: red;'>$error_db</p>"; } ?>
                                    <?php if (isset($error_sms)) { echo "<p style='color: red;'>$error_sms</p>"; } ?>
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
