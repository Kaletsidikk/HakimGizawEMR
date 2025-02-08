<?php
function check_login() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
        header("Location: login.php");
        exit;
    }
}
?>
