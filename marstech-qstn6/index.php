<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internet Usage Bill Calculator</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            width: 100%;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .form-container, .bill-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            flex: 1;
            min-width: 300px;
        }
        
        .form-container {
            border-top: 5px solid #3498db;
        }
        
        .bill-container {
            border-top: 5px solid #2ecc71;
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }
        
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 5px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
        }
        
        .radio-option input {
            margin-right: 8px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .bill {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
            border: 1px solid #eee;
        }
        
        .bill h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .customer-info {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .customer-info p {
            margin-bottom: 5px;
            font-size: 16px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #ecf0f1;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #ecf0f1;
        }
        
        .total-amount {
            color: #e74c3c;
            font-size: 24px;
        }
        
        .breakdown {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #3498db;
        }
        
        .breakdown h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .breakdown p {
            margin-bottom: 5px;
        }
        
        .empty-bill {
            text-align: center;
            color: #7f8c8d;
            padding: 40px 20px;
        }
        
        .empty-bill i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #bdc3c7;
        }
        
        .form-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .clear-btn {
            background-color: #e74c3c;
        }
        
        .clear-btn:hover {
            background-color: #c0392b;
        }
        
        .bill-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .print-btn {
            background-color: #2ecc71;
        }
        
        .print-btn:hover {
            background-color: #27ae60;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .form-container, .bill-container {
                width: 100%;
            }
            
            .form-buttons, .bill-buttons {
                flex-direction: column;
            }
        }
        
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .bill-container, .bill-container * {
                visibility: visible;
            }
            
            .bill-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: none;
            }
            
            .bill-buttons, .form-container {
                display: none !important;
            }
            
            .bill {
                box-shadow: none;
                border: 1px solid #000;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Internet Usage Bill Calculator</h1>
        
        <div class="form-container">
            <h2>Customer Information</h2>
            <form method="POST" action="" id="billForm">
                <div class="form-group">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" id="customer_name" name="customer_name" required 
                           value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="package">Internet Package</label>
                    <select id="package" name="package" required>
                        <option value="">Select a package</option>
                        <option value="Basic" <?php echo (isset($_POST['package']) && $_POST['package'] == 'Basic') ? 'selected' : ''; ?>>Basic (Rs. 760/month)</option>
                        <option value="Web Lite" <?php echo (isset($_POST['package']) && $_POST['package'] == 'Web Lite') ? 'selected' : ''; ?>>Web Lite (Rs. 1,520/month)</option>
                        <option value="Any Blast" <?php echo (isset($_POST['package']) && $_POST['package'] == 'Any Blast') ? 'selected' : ''; ?>>Any Blast (Rs. 2,340/month)</option>
                        <option value="Family Plan" <?php echo (isset($_POST['package']) && $_POST['package'] == 'Family Plan') ? 'selected' : ''; ?>>Family Plan (Rs. 3,790/month)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="extra_gb">Extra GB Used (Beyond Package Limit)</label>
                    <input type="number" id="extra_gb" name="extra_gb" min="0" step="0.1" required 
                           value="<?php echo isset($_POST['extra_gb']) ? $_POST['extra_gb'] : '0'; ?>">
                    <small>Enter the number of extra GB used beyond your package limit</small>
                </div>
                
                <div class="form-group">
                    <label>Connection Type</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="non_fiber" name="connection_type" value="Non-fiber" 
                                   <?php echo (!isset($_POST['connection_type']) || (isset($_POST['connection_type']) && $_POST['connection_type'] == 'Non-fiber')) ? 'checked' : ''; ?> required>
                            <label for="non_fiber">Non-Fiber</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="fiber" name="connection_type" value="Fiber" 
                                   <?php echo (isset($_POST['connection_type']) && $_POST['connection_type'] == 'Fiber') ? 'checked' : ''; ?>>
                            <label for="fiber">Fiber (Additional Rs. 760 rental)</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" name="calculate">Calculate Bill</button>
                    <button type="button" class="clear-btn" onclick="clearForm()">Clear Form</button>
                </div>
            </form>
            
            <div class="breakdown">
                <h4>Extra GB Charges Breakdown:</h4>
                <p><strong>1 GB to 4 GB:</strong> Rs. 100 per GB</p>
                <p><strong>5 GB to 19 GB:</strong> Rs. 85 per GB</p>
                <p><strong>20 GB to 49 GB:</strong> Rs. 75 per GB</p>
                <p><strong>50 GB upwards:</strong> Rs. 60 per GB</p>
                <p><strong>Example for 54 extra GB:</strong> (4 × 100) + (15 × 85) + (30 × 75) + (5 × 60) = Rs. 4,225</p>
            </div>
        </div>
        
        <div class="bill-container">
            <h2>Internet Usage Summary</h2>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate'])) {
                // Get form data
                $customer_name = htmlspecialchars($_POST['customer_name']);
                $package = $_POST['package'];
                $extra_gb = floatval($_POST['extra_gb']);
                $connection_type = $_POST['connection_type'];
                
                // Package rental charges
                $package_rentals = [
                    'Basic' => 760,
                    'Web Lite' => 1520,
                    'Any Blast' => 2340,
                    'Family Plan' => 3790
                ];
                
                // Calculate package rental
                $package_rental = $package_rentals[$package];
                
                // Calculate extra GB charges
                $extra_gb_charge = 0;
                $breakdown_text = "";
                
                // Tiered pricing for extra GB
                $remaining_gb = $extra_gb;
                
                // Tier 1: 1-4 GB at Rs. 100
                if ($remaining_gb > 0) {
                    $tier1_gb = min(4, $remaining_gb);
                    $tier1_charge = $tier1_gb * 100;
                    $extra_gb_charge += $tier1_charge;
                    $remaining_gb -= $tier1_gb;
                    
                    if ($tier1_gb > 0) {
                        $breakdown_text .= "($tier1_gb × 100)";
                    }
                }
                
                // Tier 2: 5-19 GB at Rs. 85
                if ($remaining_gb > 0) {
                    $tier2_gb = min(15, $remaining_gb); // 5-19 is 15 GB range
                    $tier2_charge = $tier2_gb * 85;
                    $extra_gb_charge += $tier2_charge;
                    $remaining_gb -= $tier2_gb;
                    
                    if ($tier2_gb > 0) {
                        if (!empty($breakdown_text)) $breakdown_text .= " + ";
                        $breakdown_text .= "($tier2_gb × 85)";
                    }
                }
                
                // Tier 3: 20-49 GB at Rs. 75
                if ($remaining_gb > 0) {
                    $tier3_gb = min(30, $remaining_gb); // 20-49 is 30 GB range
                    $tier3_charge = $tier3_gb * 75;
                    $extra_gb_charge += $tier3_charge;
                    $remaining_gb -= $tier3_gb;
                    
                    if ($tier3_gb > 0) {
                        if (!empty($breakdown_text)) $breakdown_text .= " + ";
                        $breakdown_text .= "($tier3_gb × 75)";
                    }
                }
                
                // Tier 4: 50+ GB at Rs. 60
                if ($remaining_gb > 0) {
                    $tier4_charge = $remaining_gb * 60;
                    $extra_gb_charge += $tier4_charge;
                    
                    if (!empty($breakdown_text)) $breakdown_text .= " + ";
                    $breakdown_text .= "($remaining_gb × 60)";
                }
                
                // Calculate fiber rental
                $fiber_rental = ($connection_type == 'Fiber') ? 760 : 0;
                
                // Calculate total
                $total = $package_rental + $extra_gb_charge + $fiber_rental;
                
                // Format currency for display
                function format_currency($amount) {
                    return number_format($amount, 2);
                }
                
                // Display bill
                echo '<div class="bill" id="printableBill">';
                echo '<h3>Internet Usage Summary</h3>';
                
                echo '<div class="customer-info">';
                echo '<p><strong>Customer Name:</strong> ' . $customer_name . '</p>';
                echo '<p><strong>Internet Package:</strong> ' . $package . '</p>';
                echo '<p><strong>Connection Type:</strong> ' . $connection_type . '</p>';
                if ($extra_gb > 0) {
                    echo '<p><strong>Extra GB Used:</strong> ' . $extra_gb . ' GB</p>';
                }
                echo '</div>';
                
                echo '<table>';
                echo '<tr>';
                echo '<th>Description</th>';
                echo '<th>Amount (Rs.)</th>';
                echo '</tr>';
                
                echo '<tr>';
                echo '<td>Monthly Package Rental (' . $package . ')</td>';
                echo '<td>' . format_currency($package_rental) . '</td>';
                echo '</tr>';
                
                if ($extra_gb > 0) {
                    echo '<tr>';
                    echo '<td>Extra Data Charges (' . $extra_gb . ' GB)</td>';
                    echo '<td>' . format_currency($extra_gb_charge) . '</td>';
                    echo '</tr>';
                }
                
                if ($fiber_rental > 0) {
                    echo '<tr>';
                    echo '<td>Fiber Connection Rental</td>';
                    echo '<td>' . format_currency($fiber_rental) . '</td>';
                    echo '</tr>';
                }
                
                echo '<tr class="total-row">';
                echo '<td><strong>Total Amount</strong></td>';
                echo '<td class="total-amount">' . format_currency($total) . '</td>';
                echo '</tr>';
                
                echo '</table>';
                
                // Show calculation breakdown if extra GB used
                if ($extra_gb > 0) {
                    echo '<div class="breakdown">';
                    echo '<h4>Calculation Breakdown:</h4>';
                    echo '<p><strong>Package Rental:</strong> ' . format_currency($package_rental) . '</p>';
                    
                    if ($extra_gb_charge > 0) {
                        echo '<p><strong>Extra GB Charges (' . $extra_gb . ' GB):</strong> ' . $breakdown_text . ' = ' . format_currency($extra_gb_charge) . '</p>';
                    }
                    
                    if ($fiber_rental > 0) {
                        echo '<p><strong>Fiber Rental:</strong> ' . format_currency($fiber_rental) . '</p>';
                    }
                    
                    echo '<p><strong>Total Calculation:</strong> ' . format_currency($package_rental);
                    
                    if ($extra_gb_charge > 0) {
                        echo ' + ' . format_currency($extra_gb_charge);
                    }
                    
                    if ($fiber_rental > 0) {
                        echo ' + ' . format_currency($fiber_rental);
                    }
                    
                    echo ' = <strong>' . format_currency($total) . '</strong></p>';
                    echo '</div>';
                }
                
                echo '</div>'; // Close bill div
                
                // Add Print and Clear buttons
                echo '<div class="bill-buttons">';
                echo '<button class="print-btn" onclick="printBill()"><i class="fas fa-print"></i> Print Summary</button>';
                echo '<button class="clear-btn" onclick="clearForm()"><i class="fas fa-redo"></i> New Calculation</button>';
                echo '</div>';
                
            } else {
                echo '<div class="empty-bill">';
                echo '<i class="fas fa-file-invoice-dollar"></i>';
                echo '<p>Please fill out the customer information and click "Calculate Bill" to see your internet usage summary here.</p>';
                echo '<p>The summary will display the package charges, extra data charges, and total amount.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    
    <script>
        // Function to print bill
        function printBill() {
            window.print();
        }
        
        // Function to clear form and reset bill
        function clearForm() {
            // Reset the form
            document.getElementById('billForm').reset();
            
            // Reset radio buttons to default (first option)
            const connectionRadios = document.getElementsByName('connection_type');
            if (connectionRadios.length > 0) {
                connectionRadios[0].checked = true;
            }
            
            // Reset package dropdown to first option
            document.getElementById('package').selectedIndex = 0;
            
            // Clear the bill container
            const billContainer = document.querySelector('.bill-container');
            if (billContainer) {
                billContainer.innerHTML = `
                    <h2>Internet Usage Summary</h2>
                    <div class="empty-bill">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Please fill out the customer information and click "Calculate Bill" to see your internet usage summary here.</p>
                        <p>The summary will display the package charges, extra data charges, and total amount.</p>
                    </div>
                `;
            }
            
            // Show a confirmation message
            alert('Form cleared successfully! You can now enter new customer information.');
        }
        
        // Auto-calculate and show breakdown as user types
        document.addEventListener('DOMContentLoaded', function() {
            const extraGBInput = document.getElementById('extra_gb');
            if (extraGBInput) {
                extraGBInput.addEventListener('input', function() {
                    updateBreakdownPreview(this.value);
                });
            }
            
            // Set default extra GB to 0 if empty
            if (extraGBInput && !extraGBInput.value) {
                extraGBInput.value = '0';
            }
        });
        
        // Function to update breakdown preview
        function updateBreakdownPreview(extraGB) {
            const extraGBFloat = parseFloat(extraGB) || 0;
            let breakdownText = "";
            let totalCharge = 0;
            
            if (extraGBFloat > 0) {
                let remaining = extraGBFloat;
                
                // Tier 1: 1-4 GB
                if (remaining > 0) {
                    const tier1 = Math.min(4, remaining);
                    const tier1Charge = tier1 * 100;
                    totalCharge += tier1Charge;
                    remaining -= tier1;
                    
                    if (tier1 > 0) {
                        breakdownText = `${tier1} GB × Rs. 100 = Rs. ${tier1Charge.toFixed(2)}`;
                    }
                }
                
                // Tier 2: 5-19 GB
                if (remaining > 0) {
                    const tier2 = Math.min(15, remaining);
                    const tier2Charge = tier2 * 85;
                    totalCharge += tier2Charge;
                    remaining -= tier2;
                    
                    if (tier2 > 0) {
                        if (breakdownText) breakdownText += "<br>";
                        breakdownText += `${tier2} GB × Rs. 85 = Rs. ${tier2Charge.toFixed(2)}`;
                    }
                }
                
                // Tier 3: 20-49 GB
                if (remaining > 0) {
                    const tier3 = Math.min(30, remaining);
                    const tier3Charge = tier3 * 75;
                    totalCharge += tier3Charge;
                    remaining -= tier3;
                    
                    if (tier3 > 0) {
                        if (breakdownText) breakdownText += "<br>";
                        breakdownText += `${tier3} GB × Rs. 75 = Rs. ${tier3Charge.toFixed(2)}`;
                    }
                }
                
                // Tier 4: 50+ GB
                if (remaining > 0) {
                    const tier4Charge = remaining * 60;
                    totalCharge += tier4Charge;
                    
                    if (breakdownText) breakdownText += "<br>";
                    breakdownText += `${remaining.toFixed(1)} GB × Rs. 60 = Rs. ${tier4Charge.toFixed(2)}`;
                }
                
                // Update the breakdown preview
                const breakdownDiv = document.querySelector('.breakdown');
                if (breakdownDiv && totalCharge > 0) {
                    // Add or update the preview section
                    let previewSection = breakdownDiv.querySelector('.preview-section');
                    if (!previewSection) {
                        previewSection = document.createElement('div');
                        previewSection.className = 'preview-section';
                        previewSection.style.marginTop = '10px';
                        previewSection.style.padding = '10px';
                        previewSection.style.backgroundColor = '#e8f4fc';
                        previewSection.style.borderRadius = '5px';
                        breakdownDiv.appendChild(previewSection);
                    }
                    
                    previewSection.innerHTML = `
                        <h4>Preview for ${extraGBFloat} extra GB:</h4>
                        <p>${breakdownText}</p>
                        <p><strong>Total Extra GB Charges: Rs. ${totalCharge.toFixed(2)}</strong></p>
                    `;
                }
            } else {
                // Remove preview section if no extra GB
                const previewSection = document.querySelector('.preview-section');
                if (previewSection) {
                    previewSection.remove();
                }
            }
        }
    </script>
</body>
</html>