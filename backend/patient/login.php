<?php
session_start();
include('assets/inc/config.php');

// Initialize the error_message variable
$error_message = "";

// Check if the user is already logged in
if (isset($_SESSION['pat_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login attempt
if (isset($_POST['login'])) {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the username exists
    $query = "SELECT * FROM his_patients WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user is found
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $patient['password'])) {
            // Set session variable
            $_SESSION['pat_id'] = $patient['pat_id'];
            $_SESSION['username'] = $patient['username'];

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "No user found with that username!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and Background */
                body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #024d85,rgb(8, 83, 141));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            margin: 0;
        }
        /* Login Container */
        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            text-align: center;
            transform: translateY(-50px);
            animation: slideUp 0.6s ease-in-out forwards;
        }

        /* Heading */
        h2 {
            color: #fff;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Error Message */
        .error-message {
            color: #f44336; /* Red error color */
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Form */
        .login-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        /* Labels */
        label {
            display: block;
            text-align: left;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: bold;
            color: #ffffff; /* Gold color for labels */
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #fff;
            border-radius: 5px;
            background-color: #fbf6f6;
            color:#000000;
            outline: none;
            transition: border 0.3s, background-color 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #fbf6f6; /* Focus border color change */
            background-color: #dee7eb
        }

        /* Button */
       .btn {
            padding: 12px;
            background-color: #127cc7;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background-color: #54a7e1; /* Lighter coral on hover */
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Register Link */
        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #fff;
        }

        .register-link a {
            color: #ffd700; /* Gold link color */
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .login-container {
                width: 80%;
                padding: 30px;
            }

            h2 {
                font-size: 24px;
            }

            .form-group input {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        /* Slide Up Animation */
        @keyframes slideUp {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Debre berhan University Hakim Gezaw Hospital Patient Portal</h2>
        <h2>Patient Login</h2>
        
        <!-- Display error message if it exists -->
        <?php if ($error_message != "") { echo "<p class='error-message'>$error_message</p>"; } ?>
        
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn">Login</button>
        </form>

        <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
