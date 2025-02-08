<?php
// Start the session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include configuration file
include('config.php'); // Adjust the path as per your directory structure

// Additional security headers (optional but recommended)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap-4.1.3.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/patient-portal.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/logo/favicon.png" type="image/x-icon">
</head>
<body>
    <!-- Header Section -->
    <header class="header-section">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="dashboard.php">Hakim Gizaw Hospital</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
