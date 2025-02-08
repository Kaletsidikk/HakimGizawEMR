<?php
include('assets/inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Validate token
    $stmt = $mysqli->prepare("SELECT username FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $username = $result->fetch_assoc()['username'];

        // Update password
        $stmt = $mysqli->prepare("UPDATE his_patients SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);
        $stmt->execute();

        // Delete the token
        $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "Password reset successfully. <a href='login.php'>Login here</a>.";
    } else {
        echo "Invalid or expired token.";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label>New Password:</label>
            <input type="password" name="new_password" required><br>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
