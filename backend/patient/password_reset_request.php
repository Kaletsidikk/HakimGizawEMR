<?php
session_start();
include('assets/inc/config.php'); // Database connection

$error_message = "";
$success_message = "";

// When the reset form is submitted
if (isset($_POST['reset_request'])) {
    $username = $_POST['username']; // Get the username input

    // Ensure the username exists in the database
    $stmt = $mysqli->prepare("SELECT pat_id FROM his_patients WHERE username = ?");
    if (!$stmt) {
        die('MySQL Error: ' . $mysqli->error);  // Check and output MySQL error
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists
    if ($stmt->num_rows > 0) {
        // Generate a unique token using openssl_random_pseudo_bytes (for PHP 5.6)
        $token = bin2hex(openssl_random_pseudo_bytes(16)); // 16 bytes = 32 characters
        $expiration_time = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expires in 1 hour

        // Store the token in the database
        $stmt = $mysqli->prepare("UPDATE his_patients SET reset_token = ?, reset_token_expiration = ? WHERE username = ?");
        if (!$stmt) {
            die('MySQL Error: ' . $mysqli->error);  // Check and output MySQL error
        }
        $stmt->bind_param("sss", $token, $expiration_time, $username);
        if ($stmt->execute()) {
            // Token is stored, now send a reset message (you can use SMS, or a link for a web-based reset)
            // For simplicity, let's assume you're logging it for now.
            $success_message = "A password reset link has been sent. Please check your communication channel.";
        } else {
            die('MySQL Error: ' . $stmt->error);  // Display specific MySQL error from statement execution
        }
    } else {
        $error_message = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Password Reset</h2>

        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Enter your username:</label>
            <input type="text" name="username" required>
            <button type="submit" name="reset_request">Request Password Reset</button>
        </form>
    </div>
</body>
</html>
