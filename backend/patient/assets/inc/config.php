<?php
// assets/inc/config.php
$mysqli = new mysqli("localhost", "root", "", "hmisphp");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
