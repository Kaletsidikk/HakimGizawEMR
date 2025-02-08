<?php
session_start();
include('assets/inc/config.php'); // Database connection

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $pat_fname = trim($_POST['pat_fname']);
    $pat_lname = trim($_POST['pat_lname']);
    $pat_dob = $_POST['pat_dob']; // Should be in YYYY-MM-DD format
    $pat_age = intval($_POST['pat_age']);
    $pat_number = trim($_POST['pat_number']);
    $pat_addr = trim($_POST['pat_addr']);
    $pat_phone = trim($_POST['pat_phone']);
    $pat_type = trim($_POST['pat_type']);
    $pat_ailment = trim($_POST['pat_ailment']);
    $pat_discharge_status = trim($_POST['pat_discharge_status']);
    $pat_literacy_status = $_POST['pat_literacy_status'];
    $pat_access_request = $_POST['pat_access_request'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement
        $stmt = $mysqli->prepare("INSERT INTO his_patients (pat_fname, pat_lname, pat_dob, pat_age, pat_number, pat_addr, pat_phone, pat_type, pat_ailment, pat_discharge_status, pat_literacy_status, pat_access_request, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            $error_message = "Error preparing the statement: " . $mysqli->error;
        } else {
            $stmt->bind_param("sssisssssssssi", $pat_fname, $pat_lname, $pat_dob, $pat_age, $pat_number, $pat_addr, $pat_phone, $pat_type, $pat_ailment, $pat_discharge_status, $pat_literacy_status, $pat_access_request, $username, $hashed_password);
            
            if ($stmt->execute()) {
                $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                if ($mysqli->errno === 1062) { // Duplicate entry
                    $error_message = "Username already exists. Please choose another.";
                } else {
                    $error_message = "Registration failed: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient Registration - Hakim Gizaw Hospital</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Header */
        header {
            background: #024d85;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color:rgb(248, 248, 245);
        }

        header nav ul {
            list-style: none;
            display: flex;
        }

        header nav ul li {
            margin-left: 15px;
        }

        header nav ul li a {
            text-decoration: none;
            color: #fff;
            transition: color 0.3s;
        }

        header nav ul li a:hover {
            color:rgb(255, 255, 255);
        }

        /* Responsive Menu */
        @media (max-width: 768px) {
            header nav ul {
                display: none;
                flex-direction: column;
            }

            header nav ul.show {
                display: flex;
                background: rgba(0, 0, 0, 0.8);
                padding: 10px;
            }

            header .menu-toggle {
                display: block;
                cursor: pointer;
                font-size: 1.5rem;
                color: #fff;
            }
        }

        @media (min-width: 769px) {
            header .menu-toggle {
                display: none;
            }
        }

        /* Form Container */
        .container {
            background: #fff;
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #024d85;
        }

        .success, .error {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background: #024d85;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        form button:hover {
            background: #026bb3;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="dashboard.php" class="navbar-brand">Hakim Gizaw Hospital</a>
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Container -->
    <div class="container">
        <h2>Patient Registration</h2>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="pat_fname" required>
            
            <label>Last Name:</label>
            <input type="text" name="pat_lname" required>
            
            <label>Date of Birth:</label>
            <input type="date" name="pat_dob" required>
            
            <label>Age:</label>
            <input type="number" name="pat_age" required>
            
            <label>Patient Number:</label>
            <input type="text" name="pat_number" required>
            
            <label>Address:</label>
            <input type="text" name="pat_addr" required>
            
            <label>Phone:</label>
            <input type="text" name="pat_phone" required>
            
            <label>Patient Type:</label>
            <select name="pat_type" required>
                <option value="">Choose</option>
                <option value="InPatient">InPatient</option>
                <option value="OutPatient">OutPatient</option>
            </select>
            
            <label>Ailment:</label>
            <input type="text" name="pat_ailment" required>
            
            <label>Discharge Status:</label>
            <input type="text" name="pat_discharge_status" required>
            
            <label>Literacy Status:</label>
            <select name="pat_literacy_status" required>
                <option value="Literate">Literate</option>
                <option value="Illiterate">Illiterate</option>
            </select>
            
            <label>Request Portal Access:</label>
            <select name="pat_access_request" required>
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>
            
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        function toggleMenu() {
            const nav = document.querySelector('header nav ul');
            nav.classList.toggle('show');
        }
    </script>
</body>
</html>
