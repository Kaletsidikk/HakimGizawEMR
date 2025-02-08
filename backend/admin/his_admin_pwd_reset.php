<?php
// Start the session and include database configuration
session_start();
include('assets/inc/config.php');

// Check if the reset form is submitted
if (isset($_POST['reset_pwd'])) {
    // Capture form data
    $email = $_POST['email'];
    $token = sha1(md5($_POST['token']));
    $status = $_POST['status'];
    $pwd = $_POST['pwd'];
    
    // SQL query to insert captured values
    $query = "INSERT INTO his_pwdresets (email, token, status, pwd) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    // Bind parameters to the SQL query
    $stmt->bind_param('ssss', $email, $token, $status, $pwd);
    
    // Execute query
    if ($stmt->execute()) {
        $success = "Check your inbox for password reset instructions";
    } else {
        $err = "Please try again later.";
    }

    $stmt->close();
}

// Generate temporary password and token
$length_pwd = 10;
$length_token = 30;
$temp_pwd = substr(str_shuffle('0123456789QWERTYUIOPPLKJHGFDSAZCVBNMqwertyuioplkjhgfdsazxcvbnm'), 1, $length_pwd);
$_token = substr(str_shuffle('0123456789QWERTYUIOPPLKJHGFDSAZCVBNMqwertyuioplkjhgfdsazxcvbnm'), 1, $length_token);

// Include PHPMailer files

require 'Hospital-Management-System-main/backend/admin/assets/PHPMailer-master/PHPMailer-master/get_oauth_token.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer();

// Check if the form is submitted and send email
if (isset($success)) {
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'youremail@gmail.com'; // Use your Gmail address here
        $mail->Password = 'yourpassword'; // Use your Gmail password or App Password here
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // SMTP Port

        // Recipients
        $mail->setFrom('youremail@gmail.com', 'Your Name');
        $mail->addAddress($email); // The email that user entered

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Instructions';
        $mail->Body = 'Please click the link below to reset your password: <br> 
                        <a href="http://localhost/HakimGizawEMR/Hospital-Management-System-main/reset_password.php?token=' . $_token . '">Reset Password</a>';

        // Send email
        if ($mail->send()) {
            $success = "An email has been sent to you with instructions to reset your password.";
        } else {
            $err = "Message could not be sent.";
        }
    } catch (Exception $e) {
        $err = "Mailer Error: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hakim Gizaw Hospital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css">

    <!-- SweetAlert for success or error messages -->
    <script src="assets/js/swal.js"></script>

    <?php if (isset($success)) { ?>
        <script>
            setTimeout(function () {
                swal("Success", "<?php echo $success; ?>", "success");
            }, 100);
        </script>
    <?php } ?>

    <?php if (isset($err)) { ?>
        <script>
            setTimeout(function () {
                swal("Failed", "<?php echo $err; ?>", "error");
            }, 100);
        </script>
    <?php } ?>
</head>

<body class="authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <a href="his_doc_reset_pwd.php">
                                    <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Enter your email address and we'll send you an email with instructions to reset your password.</p>
                            </div>

                            <form method="post">
                                <div class="form-group mb-3">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                </div>

                                <!-- Hidden fields for token and temporary password -->
                                <div class="form-group mb-3" style="display:none">
                                    <label for="emailaddress">Reset Token</label>
                                    <input class="form-control" name="token" type="text" value="<?php echo $_token; ?>">
                                </div>
                                <div class="form-group mb-3" style="display:none">
                                    <label for="emailaddress">Reset Temp Pwd</label>
                                    <input class="form-control" name="pwd" type="text" value="<?php echo $temp_pwd; ?>">
                                </div>
                                <div class="form-group mb-3" style="display:none">
                                    <label for="emailaddress">Status</label>
                                    <input class="form-control" name="status" type="text" value="Pending">
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button name="rest_pwd" class="btn btn-primary btn-block" type="submit">Reset Password</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Back to <a href="index.php" class="text-white ml-1"><b>Log in</b></a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php include("assets/inc/footer1.php");?>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
