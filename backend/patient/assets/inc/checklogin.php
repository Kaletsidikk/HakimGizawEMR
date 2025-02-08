<?php
function check_login() {
    // Start session if not already started
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if ad_id is empty
    if (empty($_SESSION['ad_id'])) {
        // Get the host and URI to redirect
        $host = $_SERVER['SERVER_NAME'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "index.php"; // The login page
        $_SESSION["ad_id"] = ""; // Clear the session

        // Redirect to login page
        header("Location: http://$host$uri/$extra");
        exit(); // Make sure the script stops after the redirect
    }
}
?>
