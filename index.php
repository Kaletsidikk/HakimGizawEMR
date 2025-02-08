<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hakim Gizaw Hospital</title>
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/x-icon">
    
    <!-- Font Awesome for Hamburger and Close Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- External CSS File -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="header-area">
        <div id="header" id="home">
            <div id="nav-menu-container">
            <a href="#" class="logo" class="logo hover-shining">
            <img src="./assets/images/logo.png" width="136" height="46" alt="Hakim gizaw Home">
          </a>
                <!-- Hamburger Toggle (Moved to Top-Right) -->
                <input type="checkbox" id="menu-toggle" class="menu-toggle-checkbox">
                <label for="menu-toggle" class="hamburger">
                    <i class="fas fa-bars"></i> <!-- Font Awesome Hamburger Icon -->
                </label>

                <!-- Normal Menu (Desktop View) -->
                <ul class="nav-menu">
                    <li class="menu-active"><a href="index.php">Home</a></li>
                    <li><a href="backend/doc/index.php">Staff Login</a></li>
                    <li><a href="backend/admin/index.php">Administrator Login</a></li>
                    <li><a href="backend/patient/login.php">Patient Portal</a></li>
                </ul>

                <!-- Toggle Menu (Mobile View) -->
                <ul class="nav-menu toggle-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="backend/doc/index.php">Staff Login</a></li>
                    <li><a href="backend/admin/index.php">Administrator Login</a></li>
                    <li><a href="backend/patient/login.php">Patient Portal</a></li>
                    <li class="close-menu">
                        <label for="menu-toggle" class="close-icon">
                            <i class="fas fa-times"></i> <!-- Font Awesome Close Icon -->
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Banner Area -->
    <section class="banner-area">
        <video autoplay muted loop class="video-background">
            <source src="assets/videos/hospital-background.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h4>Caring for better life</h4>
                    <h1>Debre Berhan University<br>Hakim Gizaw Hospital</h1>               
                    <p>HMS is awarded as one of the Top Hospital Management Systems.</p>
                    <a href="https://kaletsidikk.github.io/HakimGizaw-Website/" class="btn-primary">Visit our Website</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Patient Portal Information Section -->
    <section class="patient-portal-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Access Your Health Records Online</h2>
                    <p>Log in to the Patient Portal to view your medical history, request appointments, and more.</p>
                    <a href="backend/patient/login.php" class="btn-primary">Patient Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Link to External JS File -->
    <script src="assets/jas/scripts.js"></script>
</body>
</html>
