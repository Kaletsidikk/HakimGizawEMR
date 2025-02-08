<?php
session_start();
include('assets/inc/config.php');

// Check if the user is logged in
if (!isset($_SESSION['pat_id'])) {
    header("Location: login.php");
    exit();
}

// Handle language selection
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Load language file
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'; // Default to English
$langFile = "languages/{$lang}.php";
if (file_exists($langFile)) {
    $texts = include($langFile);
} else {
    $texts = include("languages/en.php"); // Fallback to English
}

$pat_id = $_SESSION['pat_id'];

// Retrieve Patient Data
$query = "SELECT * FROM his_patients WHERE pat_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $pat_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// Handle AI Consultation Form Submission using local DeepSeek R1 API
if (isset($_POST['ask_ai'])) {
    $patient_query = $_POST['patient_query'];
    $ai_response = askLocalAI($patient_query);
}

// Function to call local DeepSeek R1 API using cURL for better error handling
function askLocalAI($query) {
    $url = "http://localhost:11434/api/generate"; // Your API endpoint
    $data = json_encode([
        "model"  => "deepseek-r1:1.5b",
        "prompt" => $query
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result = curl_exec($ch);
    if(curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "cURL error: " . $error_msg;
    }
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($http_status != 200) {
        return "Error: Received HTTP status code " . $http_status;
    }

    // Process the streamed JSON lines:
    // Assume that each JSON object is separated by a newline.
    $lines = explode("\n", trim($result));
    $fullResponse = "";
    foreach ($lines as $line) {
        if (!empty($line)) {
            $json = json_decode($line, true);
            if (isset($json["response"])) {
                $fullResponse .= $json["response"];
            }
            // Stop if this chunk indicates the stream is done
            if (isset($json["done"]) && $json["done"] === true) {
                break;
            }
        }
    }
    return $fullResponse;
}



?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['dashboard_title']; ?></title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #024d85;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        .link {
            background-color: #024d85;
        }

        .link a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .logout-btn {
            margin-left: auto;
            color: white;
            text-decoration: none;
            padding: 10px;
            border: 1px solid #ffffff;
            border-radius: 5px;
            background-color: #024d85;
        }

        .logout-btn:hover {
            background-color: #035a9d;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(7, 7, 7, 0.2);
        }

        .dropdown {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .dropdown h3 {
            margin: 0;
            padding: 15px;
            background-color: #024d85;
            color: white;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            padding: 20px;
            border-top: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #024d85;
            color: white;
        }

        .request-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .request-form input, .request-form textarea, .request-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .request-form button {
            background-color: #024d85;
            color: white;
            cursor: pointer;
        }

        .request-form button:hover {
            background-color: #024d85;
        }

        /* AI Consultation Styles */
        #ai-consultation-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #ai-consultation-form button {
            background-color: #024d85;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #ai-consultation-form button:hover {
            background-color: #035a9d;
        }

        #ai-response {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
    <script>
        // Dropdown toggle function
        function toggleDropdown(id) {
            const content = document.getElementById(id);
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <header>
        <h1><?php echo $texts['dashboard_title']; ?></h1>
        <div class="link">
            <a href="?lang=en">English</a> | 
            <a href="?lang=am">አማርኛ</a> | 
            <a href="?lang=tig">ትግርኛ</a> |
            <a href="?lang=om">Oromo</a>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="container">
        <h2><?php echo $texts['welcome']; ?>, <?php echo htmlspecialchars($patient['pat_fname'] . ' ' . $patient['pat_lname']); ?></h2>

        <!-- Patient Profile Dropdown -->
        <div class="dropdown">
            <h3 onclick="toggleDropdown('profile')"><?php echo $texts['patient_profile']; ?></h3>
            <div id="profile" class="dropdown-content">
                <p><strong><?php echo $texts['date_of_birth']; ?>:</strong> <?php echo htmlspecialchars($patient['pat_dob']); ?></p>
                <p><strong><?php echo $texts['address']; ?>:</strong> <?php echo htmlspecialchars($patient['pat_addr']); ?></p>
                <p><strong><?php echo $texts['phone']; ?>:</strong> <?php echo htmlspecialchars($patient['pat_phone']); ?></p>
                <p><strong><?php echo $texts['ailment']; ?>:</strong> <?php echo htmlspecialchars($patient['pat_ailment']); ?></p>
                <p><strong><?php echo $texts['discharge_status']; ?>:</strong> <?php echo htmlspecialchars($patient['pat_discharge_status']); ?></p>
            </div>
        </div>

        <!-- Static Vital Information Dropdown -->
        <div class="dropdown">
            <h3 onclick="toggleDropdown('vitals')"><?php echo $texts['vital_signs']; ?></h3>
            <div id="vitals" class="dropdown-content">
                <p><strong><?php echo $texts['blood_pressure']; ?>:</strong> 120/80 mmHg</p>
                <p><strong><?php echo $texts['heart_rate']; ?>:</strong> 72 bpm</p>
                <p><strong><?php echo $texts['temperature']; ?>:</strong> 98.6°F</p>
                <p><strong><?php echo $texts['respiratory_rate']; ?>:</strong> 16 breaths/min</p>
            </div>
        </div>

        <!-- Prescriptions Dropdown -->
        <div class="dropdown">
            <h3 onclick="toggleDropdown('prescriptions')"><?php echo $texts['prescriptions']; ?></h3>
            <div id="prescriptions" class="dropdown-content">
                <table>
                    <thead>
                        <tr>
                            <th><?php echo $texts['medication']; ?></th>
                            <th><?php echo $texts['dose']; ?></th>
                            <th><?php echo $texts['issued_date']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Aspirin</td>
                            <td>500mg</td>
                            <td>2024-11-10</td>
                        </tr>
                        <tr>
                            <td>Paracetamol</td>
                            <td>250mg</td>
                            <td>2024-11-12</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Request Appointment Dropdown -->
        <div class="dropdown">
            <h3 onclick="toggleDropdown('request')"><?php echo $texts['request_appointment']; ?></h3>
            <div id="request" class="dropdown-content">
                <form class="request-form" action="request_appointment.php" method="post">
                    <label for="appointment-date"><?php echo $texts['preferred_date']; ?>:</label>
                    <input type="date" id="appointment-date" name="appointment_date" required>

                    <label for="appointment-time"><?php echo $texts['appointment_time']; ?>:</label>
                    <input type="time" id="appointment-time" name="appointment_time" required>

                    <label for="doctor-name"><?php echo $texts['doctor_name']; ?>:</label>
                    <input type="text" id="doctor-name" name="doctor_name" required>

                    <label for="phone-number"><?php echo $texts['phone_number']; ?>:</label>
                    <input type="tel" id="phone-number" name="phone_number" placeholder="+251-" required>

                    <label for="reason"><?php echo $texts['reason']; ?>:</label>
                    <textarea id="reason" name="reason" rows="4" required></textarea>

                    <button type="submit"><?php echo $texts['submit_request']; ?></button>
                </form>
            </div>
        </div>

        <!-- AI Consultation Dropdown -->
        <div class="dropdown">
            <h3 onclick="toggleDropdown('ai-consultation')"><?php echo $texts['ai_consultation']; ?></h3>
            <div id="ai-consultation" class="dropdown-content">
                <form id="ai-consultation-form" method="post">
                    <label for="patient-query"><?php echo $texts['ask_ai']; ?>:</label>
                    <textarea id="patient-query" name="patient_query" rows="4" required></textarea>
                    <button type="submit" name="ask_ai"><?php echo $texts['ask_ai']; ?></button>
                </form>
                <div id="ai-response">
                    <?php
                    if (isset($ai_response)) {
                        echo "<p><strong>" . $texts['ai_response'] . ":</strong> " . htmlspecialchars($ai_response) . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

<!-- Consultation Dropdown -->
<div class="dropdown">
    <h3 onclick="toggleDropdown('consultation')"><?php echo $texts['consultation']; ?></h3>
    <div id="consultation" class="dropdown-content">
        <p>
            <strong><?php echo $texts['video_chat_with_doctor']; ?>:</strong>
            <button onclick="redirectToChat()">Start Video Chat</button>
        </p>
    </div>
</div>

<script>
    function redirectToChat() {
        window.location.href = "https://meetup-production-40ff.up.railway.app/"; // or use your local IP
    }
</script>

</div>

    </div>
</body>
</html>
