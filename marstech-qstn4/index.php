<?php
// Start session
session_start();

// Constants for conversions
define('KG_TO_POUNDS', 2.205);
define('CM_TO_INCH', 2.54);
define('INCHES_PER_FOOT', 12);

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Validate all form inputs
 */
function validateInputs($name, $age, $address, $contact, $weight, $height) {
    if (empty($name) || empty($address) || empty($contact)) {
        return false;
    }
    
    if ($age < 1 || $age > 120) {
        return false;
    }
    
    if ($weight <= 0 || $height <= 0) {
        return false;
    }
    
    // Simple contact validation
    if (!preg_match('/^[0-9\-\s\+\(\)]{10,}$/', $contact)) {
        return false;
    }
    
    return true;
}

/**
 * Determine BMI category based on value
 */
function getBMICategory($bmi) {
    if ($bmi < 18.5) {
        return "Under Healthy Weight";
    } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
        return "Healthy Weight";
    } elseif ($bmi >= 25 && $bmi <= 29.9) {
        return "Overweight";
    } elseif ($bmi >= 30 && $bmi <= 34.9) {
        return "Obese I";
    } elseif ($bmi >= 35 && $bmi <= 39.9) {
        return "Obese II";
    } else {
        return "Obese III";
    }
}

/**
 * Load BMI chart from text file and convert to array
 */
function loadBMIChart($file_path = 'data/bmi_chart.txt') {
    $chart = [];
    
    if (file_exists($file_path)) {
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $parts = explode(':', $line);
            if (count($parts) == 2) {
                $category = trim($parts[0]);
                $range = trim($parts[1]);
                $chart[$category] = $range;
            }
        }
    }
    
    // If file doesn't exist or is empty, use default chart
    if (empty($chart)) {
        $chart = [
            "Under Healthy Weight" => "< 18.5",
            "Healthy Weight" => "18.5 - 24.9",
            "Overweight" => "25 - 29.9",
            "Obese I" => "30 - 34.9",
            "Obese II" => "35 - 39.9",
            "Obese III" => ">= 40"
        ];
    }
    
    return $chart;
}

/**
 * Convert centimeters to feet and inches string
 */
function cmToFeetInches($cm) {
    $inches = $cm / CM_TO_INCH;
    $feet = floor($inches / 12);
    $remaining_inches = round($inches % 12, 1);
    return $feet . "' " . $remaining_inches . "''";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get form data
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $age = intval($_POST['age']);
    $address = htmlspecialchars(trim($_POST['address']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $weight_kg = floatval($_POST['weight_kg']);
    $height_cm = floatval($_POST['height_cm']);
    
    // Validate inputs
    if (validateInputs($full_name, $age, $address, $contact, $weight_kg, $height_cm)) {
        // Calculate BMI
        $height_m = $height_cm / 100;
        $bmi = $weight_kg / ($height_m * $height_m);
        $bmi = round($bmi, 1);
        
        // Convert to pounds and inches
        $weight_pounds = round($weight_kg * KG_TO_POUNDS, 1);
        $height_inches = round($height_cm / CM_TO_INCH, 1);
        
        // Convert to feet and inches format
        $height_feet = floor($height_inches / 12);
        $remaining_inches = round($height_inches % 12, 1);
        $height_formatted = $height_feet . "' " . $remaining_inches . "''";
        
        // Determine BMI category
        $category = getBMICategory($bmi);
        
        // Load BMI chart
        $bmi_chart = loadBMIChart();
        
        // Store data in session for display
        $_SESSION['bmi_data'] = [
            'full_name' => $full_name,
            'age' => $age,
            'address' => $address,
            'contact' => $contact,
            'weight_kg' => $weight_kg,
            'height_cm' => $height_cm,
            'weight_pounds' => $weight_pounds,
            'height_inches' => $height_formatted,
            'bmi' => $bmi,
            'category' => $category,
            'bmi_chart' => $bmi_chart
        ];
        
        // Redirect to show report
        header("Location: index.php?report=true");
        exit();
        
    } else {
        $error_message = "Please fill all fields with valid data!";
    }
}

// Check if we should show report
$show_report = isset($_GET['report']) && isset($_SESSION['bmi_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $show_report ? 'BMI Report' : 'BMI Calculator'; ?></title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: #333;
        line-height: 1.6;
        padding: 20px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    h1 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 10px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    .subtitle {
        text-align: center;
        color: #7f8c8d;
        margin-bottom: 30px;
        font-style: italic;
    }

    h2 {
        color: #2980b9;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    h3 {
        color: #3498db;
        margin: 20px 0 15px;
    }

    .form-container {
        background-color: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid #3498db;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
    }

    .button-group {
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .submit-btn {
        background-color: #2ecc71;
        color: white;
        flex: 2;
    }

    .submit-btn:hover {
        background-color: #27ae60;
    }

    .clear-btn {
        background-color: #e74c3c;
        color: white;
        flex: 1;
    }

    .clear-btn:hover {
        background-color: #c0392b;
    }

    .back-btn {
        background-color: #3498db;
        color: white;
        margin-top: 20px;
    }

    .back-btn:hover {
        background-color: #2980b9;
    }

    .instructions {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #9b59b6;
    }

    .instructions ul {
        list-style-type: none;
        margin-left: 10px;
    }

    .instructions li {
        margin-bottom: 8px;
        padding-left: 5px;
    }

    .category {
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 3px;
    }

    .underweight {
        background-color: #3498db;
        color: white;
    }

    .healthy {
        background-color: #2ecc71;
        color: white;
    }

    .overweight {
        background-color: #f39c12;
        color: white;
    }

    .obese1 {
        background-color: #e67e22;
        color: white;
    }

    .obese2 {
        background-color: #d35400;
        color: white;
    }

    .obese3 {
        background-color: #c0392b;
        color: white;
    }

    .conversion {
        margin-top: 15px;
        font-style: italic;
        color: #7f8c8d;
    }

    /* Report Page Styles */
    .report-container {
        background-color: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        border-left: 4px solid #2ecc71;
    }

    .personal-info {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 25px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .personal-info p {
        margin-bottom: 10px;
        font-size: 16px;
    }

    .bmi-result {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 25px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table th, table td {
        padding: 15px;
        text-align: center;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #3498db;
        color: white;
        font-weight: 600;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .category-label {
        font-weight: 600;
        text-align: right !important;
    }

    .bmi-chart {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 25px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .chart-content {
        font-family: 'Courier New', monospace;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        max-height: 200px;
        overflow-y: auto;
    }

    .navigation {
        text-align: center;
        margin-top: 30px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .error-message {
        background-color: #ffebee;
        color: #c62828;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border-left: 4px solid #c62828;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
        
        .container {
            padding: 15px;
        }
        
        .form-container, .instructions, .report-container {
            padding: 15px;
        }
        
        table {
            font-size: 14px;
        }
        
        table th, table td {
            padding: 10px 5px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
    </style>
</head>
<body>
    <?php if ($show_report): ?>
        <!-- Report Display -->
        <?php 
        $data = $_SESSION['bmi_data'];
        $category_class = strtolower(str_replace(' ', '', $data['category']));
        ?>
        <div class="container report-container">
            <h1>BMI Report of <?php echo $data['full_name']; ?></h1>
            
            <div class="personal-info">
                <h3>Personal Information</h3>
                <p><strong>Age:</strong> <?php echo $data['age']; ?></p>
                <p><strong>Address:</strong> <?php echo $data['address']; ?></p>
                <p><strong>Contact Number:</strong> <?php echo $data['contact']; ?></p>
            </div>
            
            <div class="bmi-result">
                <h3>BMI Calculation Results</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Weight (Pounds)</th>
                            <th>Height (Feet & Inches)</th>
                            <th>BMI Value</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $data['weight_pounds']; ?> lbs</td>
                            <td><?php echo $data['height_inches']; ?></td>
                            <td><?php echo $data['bmi']; ?></td>
                            <td><span class="category <?php echo $category_class; ?>"><?php echo $data['category']; ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Original Input</strong></td>
                            <td colspan="2"><?php echo $data['weight_kg']; ?> kg / <?php echo $data['height_cm']; ?> cm</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn submit-btn">Calculate Again</a>
                <button onclick="window.print()" class="btn back-btn">Print Report</button>
            </div>
        </div>
        
        <?php
        // Clear session data after displaying
        unset($_SESSION['bmi_data']);
        ?>
    <?php else: ?>
        <!-- BMI Calculator Form -->
        <div class="container">
            <h1>BMI Calculator</h1>
            <p class="subtitle">Calculate your Body Mass Index and understand your weight category</p>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" required placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" min="1" max="120" required placeholder="e.g., 25">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact">Contact Number:</label>
                            <input type="tel" id="contact" name="contact" required placeholder="e.g., 09123456789">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" rows="3" required placeholder="Enter your complete address"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight_kg">Weight (Kg):</label>
                            <input type="number" id="weight_kg" name="weight_kg" step="0.1" min="1" required placeholder="e.g., 65.5">
                            <p class="conversion">Approx: <span id="weight_conversion">0 lbs</span></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="height_cm">Height (cm):</label>
                            <input type="number" id="height_cm" name="height_cm" step="0.1" min="1" required placeholder="e.g., 170">
                            <p class="conversion">Approx: <span id="height_conversion">0' 0"</span></p>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" name="submit" class="btn submit-btn">Calculate BMI</button>
                        <button type="reset" id="clearBtn" class="btn clear-btn">Clear Form</button>
                    </div>
                </form>
            </div>
            
            <div class="instructions">
                <h3>BMI Categories Guide</h3>
                <ul>
                    <?php 
                    $bmi_chart = loadBMIChart();
                    foreach ($bmi_chart as $cat => $range): 
                        $cat_class = strtolower(str_replace(' ', '', $cat));
                    ?>
                        <li><span class="category <?php echo $cat_class; ?>"><?php echo $cat; ?></span>: <?php echo $range; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <script>
        // Real-time conversion display
        document.getElementById('weight_kg').addEventListener('input', function() {
            const kg = parseFloat(this.value) || 0;
            const pounds = (kg * <?php echo KG_TO_POUNDS; ?>).toFixed(1);
            document.getElementById('weight_conversion').textContent = pounds + ' lbs';
        });
        
        document.getElementById('height_cm').addEventListener('input', function() {
            const cm = parseFloat(this.value) || 0;
            const inches = cm / <?php echo CM_TO_INCH; ?>;
            const feet = Math.floor(inches / 12);
            const remainingInches = Math.round(inches % 12);
            document.getElementById('height_conversion').textContent = feet + "' " + remainingInches + '"';
        });
        
        // Clear form button
        document.getElementById('clearBtn').addEventListener('click', function() {
            document.getElementById('weight_conversion').textContent = '0 lbs';
            document.getElementById('height_conversion').textContent = '0\' 0"';
        });
        </script>
    <?php endif; ?>
</body>
</html>