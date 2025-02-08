<?php
session_start();
include('assets/inc/config.php');

// Check if the form to retrieve credentials is submitted
if (isset($_POST['retrieve_credentials'])) {
    $pat_number = $_POST['pat_number']; // Retrieve patient number from the form

    // SQL query to fetch username and password based on patient number
    $query = "SELECT username, password FROM his_patients WHERE pat_number = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die('Error preparing the SQL query: ' . $mysqli->error);
    }

    // Bind the patient number to the query
    $stmt->bind_param('s', $pat_number);

    // Execute the query
    $stmt->execute();
    $stmt->bind_result($username, $password);

    // Fetch and display the results
    if ($stmt->fetch()) {
        $retrieved_username = $username;
        $retrieved_password = $password; // This will be hashed
        $message = "Credentials retrieved successfully.";
    } else {
        $error = "No patient found with the provided patient number.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="HIS">
    <title>Retrieve Patient Credentials</title>
    
    <!-- Include CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <!-- Begin page wrapper -->
    <div id="wrapper">

        <!-- Top Navigation -->
        <?php include('assets/inc/nav.php'); ?>

        <!-- Sidebar -->
        <?php include('assets/inc/sidebar.php'); ?>

        <!-- Content -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Retrieve Patient Credentials</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Retrieve Credentials Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Display messages -->
                                    <?php if (isset($message)) : ?>
                                        <div class="alert alert-success">
                                            <?php echo $message; ?>
                                        </div>
                                        <p><strong>Username:</strong> <?php echo htmlspecialchars($retrieved_username); ?></p>
                                        <p><strong>Password (hashed):</strong> <?php echo htmlspecialchars($retrieved_password); ?></p>
                                    <?php elseif (isset($error)) : ?>
                                        <div class="alert alert-danger">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post">
                                        <div class="form-group">
                                            <label for="pat_number">Patient Number:</label>
                                            <input type="text" id="pat_number" name="pat_number" class="form-control" required>
                                        </div>
                                        <button type="submit" name="retrieve_credentials" class="btn btn-primary">Retrieve Credentials</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container -->
            </div> <!-- content -->
            
            <!-- Footer -->
            <?php include('assets/inc/footer.php'); ?>
        </div>
    </div> <!-- wrapper -->

    <!-- Include JS -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
