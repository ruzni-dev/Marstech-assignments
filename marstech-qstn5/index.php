<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservation System</title>
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
        
        .form-container, .receipt-container {
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
        
        .receipt-container {
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
        
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, input[type="date"]:focus, select:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .radio-group, .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 5px;
        }
        
        .radio-option, .checkbox-option {
            display: flex;
            align-items: center;
        }
        
        .radio-option input, .checkbox-option input {
            margin-right: 8px;
        }
        
        .activity-hours {
            display: flex;
            align-items: center;
            margin-top: 5px;
            margin-left: 20px;
        }
        
        .activity-hours input {
            width: 60px;
            margin-left: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .form-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
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
            flex: 1;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .clear-btn {
            background-color: #e74c3c;
        }
        
        .clear-btn:hover {
            background-color: #c0392b;
        }
        
        .receipt-buttons {
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
        
        .receipt {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
            border: 1px solid #eee;
        }
        
        .receipt h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
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
        
        .hotel-info {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .hotel-info p {
            margin-bottom: 5px;
        }
        
        .hidden {
            display: none;
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .empty-receipt {
            text-align: center;
            color: #7f8c8d;
            padding: 40px 20px;
        }
        
        .empty-receipt i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #bdc3c7;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .form-container, .receipt-container {
                width: 100%;
            }
            
            .form-buttons, .receipt-buttons {
                flex-direction: column;
            }
        }
        
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: none;
            }
            
            .receipt-buttons, .form-container {
                display: none !important;
            }
            
            .receipt {
                box-shadow: none;
                border: 1px solid #000;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Hotel Reservation System</h1>
        
        <div class="form-container">
            <h2>Reservation Details</h2>
            <form method="POST" action="" id="reservationForm">
                <div class="form-group">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" id="customer_name" name="customer_name" required value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="check_in">Check-in Date</label>
                    <input type="date" id="check_in" name="check_in" required value="<?php echo isset($_POST['check_in']) ? $_POST['check_in'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="check_out">Check-out Date</label>
                    <input type="date" id="check_out" name="check_out" required value="<?php echo isset($_POST['check_out']) ? $_POST['check_out'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="hotel">Hotel</label>
                    <select id="hotel" name="hotel" required>
                        <option value="">Select a hotel</option>
                        <option value="Riverside Hotel" <?php echo (isset($_POST['hotel']) && $_POST['hotel'] == 'Riverside Hotel') ? 'selected' : ''; ?>>Riverside Hotel</option>
                        <option value="Lagoon View Hotel" <?php echo (isset($_POST['hotel']) && $_POST['hotel'] == 'Lagoon View Hotel') ? 'selected' : ''; ?>>Lagoon View Hotel</option>
                        <option value="Nature Villa" <?php echo (isset($_POST['hotel']) && $_POST['hotel'] == 'Nature Villa') ? 'selected' : ''; ?>>Nature Villa</option>
                        <option value="Beach Resort" <?php echo (isset($_POST['hotel']) && $_POST['hotel'] == 'Beach Resort') ? 'selected' : ''; ?>>Beach Resort</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Room Type</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="standard_double" name="room_type" value="Standard Double" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Standard Double') ? 'checked' : ''; ?> required>
                            <label for="standard_double">Standard Double</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="deluxe_twin" name="room_type" value="Deluxe Twin Room" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Deluxe Twin Room') ? 'checked' : ''; ?>>
                            <label for="deluxe_twin">Deluxe Twin Room</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="executive_suite" name="room_type" value="Executive Suite" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Executive Suite') ? 'checked' : ''; ?>>
                            <label for="executive_suite">Executive Suite</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Activities</label>
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="spa" name="activities[]" value="Spa" <?php echo (isset($_POST['activities']) && in_array('Spa', $_POST['activities'])) ? 'checked' : ''; ?>>
                            <label for="spa">Spa</label>
                        </div>
                        <div class="activity-hours">
                            <label for="spa_hours">Hours:</label>
                            <input type="number" id="spa_hours" name="spa_hours" min="0" value="<?php echo isset($_POST['spa_hours']) ? $_POST['spa_hours'] : '0'; ?>">
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="cycling" name="activities[]" value="Cycling" <?php echo (isset($_POST['activities']) && in_array('Cycling', $_POST['activities'])) ? 'checked' : ''; ?>>
                            <label for="cycling">Cycling</label>
                        </div>
                        <div class="activity-hours">
                            <label for="cycling_hours">Hours:</label>
                            <input type="number" id="cycling_hours" name="cycling_hours" min="0" value="<?php echo isset($_POST['cycling_hours']) ? $_POST['cycling_hours'] : '0'; ?>">
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="swimming" name="activities[]" value="Swimming" <?php echo (isset($_POST['activities']) && in_array('Swimming', $_POST['activities'])) ? 'checked' : ''; ?>>
                            <label for="swimming">Swimming</label>
                        </div>
                        <div class="activity-hours">
                            <label for="swimming_hours">Hours:</label>
                            <input type="number" id="swimming_hours" name="swimming_hours" min="0" value="<?php echo isset($_POST['swimming_hours']) ? $_POST['swimming_hours'] : '0'; ?>">
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="gym" name="activities[]" value="Gym" <?php echo (isset($_POST['activities']) && in_array('Gym', $_POST['activities'])) ? 'checked' : ''; ?>>
                            <label for="gym">Gym</label>
                        </div>
                        <div class="activity-hours">
                            <label for="gym_hours">Hours:</label>
                            <input type="number" id="gym_hours" name="gym_hours" min="0" value="<?php echo isset($_POST['gym_hours']) ? $_POST['gym_hours'] : '0'; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Board Type</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="half_board" name="board_type" value="Half board" <?php echo (isset($_POST['board_type']) && $_POST['board_type'] == 'Half board') ? 'checked' : ''; ?> required>
                            <label for="half_board">Half board</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="full_board" name="board_type" value="Full board" <?php echo (isset($_POST['board_type']) && $_POST['board_type'] == 'Full board') ? 'checked' : ''; ?>>
                            <label for="full_board">Full board</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" name="reserve">Reserve</button>
                    <button type="button" class="clear-btn" id="clearFormBtn">Clear Form</button>
                </div>
            </form>
        </div>
        
        <div class="receipt-container">
            <h2>Reservation Receipt</h2>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
                // Get form data
                $customer_name = htmlspecialchars($_POST['customer_name']);
                $check_in = $_POST['check_in'];
                $check_out = $_POST['check_out'];
                $hotel = $_POST['hotel'];
                $room_type = $_POST['room_type'];
                $board_type = $_POST['board_type'];
                
                // Calculate number of days
                $date1 = new DateTime($check_in);
                $date2 = new DateTime($check_out);
                $interval = $date1->diff($date2);
                $num_days = $interval->days;
                
                // Room charges per day based on hotel and room type
                $room_charges = [
                    'Riverside Hotel' => [
                        'Standard Double' => 7500,
                        'Deluxe Twin Room' => 8500,
                        'Executive Suite' => 10000
                    ],
                    'Lagoon View Hotel' => [
                        'Standard Double' => 8500,
                        'Deluxe Twin Room' => 10000,
                        'Executive Suite' => 12500
                    ],
                    'Nature Villa' => [
                        'Standard Double' => 10000,
                        'Deluxe Twin Room' => 12500,
                        'Executive Suite' => 15000
                    ],
                    'Beach Resort' => [
                        'Standard Double' => 12500,
                        'Deluxe Twin Room' => 15000,
                        'Executive Suite' => 20000
                    ]
                ];
                
                // Activity charges per hour
                $activity_charges = [
                    'Spa' => 5000,
                    'Cycling' => 400,
                    'Swimming' => 1000,
                    'Gym' => 850
                ];
                
                // Calculate room total
                $room_price_per_day = $room_charges[$hotel][$room_type];
                $room_total = $room_price_per_day * $num_days;
                
                // Calculate board charges
                $board_charge = ($board_type == 'Full board') ? 3500 : 0;
                
                // Calculate activity charges
                $activity_total = 0;
                $activities_selected = isset($_POST['activities']) ? $_POST['activities'] : [];
                
                // Store activity details for receipt
                $activity_details = [];
                
                foreach ($activities_selected as $activity) {
                    $hours_field = strtolower(str_replace(' ', '_', $activity)) . '_hours';
                    $hours = isset($_POST[$hours_field]) ? (int)$_POST[$hours_field] : 0;
                    
                    if ($hours > 0) {
                        $activity_cost = $activity_charges[$activity] * $hours;
                        $activity_total += $activity_cost;
                        $activity_details[] = [
                            'name' => $activity,
                            'hours' => $hours,
                            'cost' => $activity_cost
                        ];
                    }
                }
                
                // Calculate total
                $total = $room_total + $board_charge + $activity_total;
                
                // Format currency for display
                function format_currency($amount) {
                    return number_format($amount, 2);
                }
                
                // Display receipt
                echo '<div class="receipt" id="printableReceipt">';
                echo '<h3>Reservation Receipt</h3>';
                echo '<div class="hotel-info">';
                echo '<p><strong>Customer Name:</strong> ' . $customer_name . '</p>';
                echo '<p><strong>Hotel:</strong> ' . $hotel . '</p>';
                echo '<p><strong>Check-in Date:</strong> ' . date('m/d/Y', strtotime($check_in)) . '</p>';
                echo '<p><strong>Check-out Date:</strong> ' . date('m/d/Y', strtotime($check_out)) . '</p>';
                echo '</div>';
                
                echo '<table>';
                echo '<tr>';
                echo '<th>Description</th>';
                echo '<th>Charges (Rs.)</th>';
                echo '</tr>';
                
                echo '<tr>';
                echo '<td>Room type: ' . $room_type . ')</td>';
                echo '<td>' . format_currency($room_total) . '</td>';
                echo '</tr>';
                
                if ($board_charge > 0) {
                    echo '<tr>';
                    echo '<td>' . $board_type . '</td>';
                    echo '<td>' . format_currency($board_charge) . '</td>';
                    echo '</tr>';
                }
                
                if (!empty($activity_details)) {
                    echo '<tr><td colspan="2"><strong>Activities</strong></td></tr>';
                    
                    foreach ($activity_details as $activity) {
                        echo '<tr>';
                        echo '<td>' . $activity['name'] . ' (' . $activity['hours'] . 'h)</td>';
                        echo '<td>' . format_currency($activity['cost']) . '</td>';
                        echo '</tr>';
                    }
                }
                
                echo '<tr class="total-row">';
                echo '<td><strong>Total</strong></td>';
                echo '<td class="total-amount">' . format_currency($total) . '</td>';
                echo '</tr>';
                
                echo '</table>';

                echo '</div>'; // Close receipt div
                
                // Add Print Receipt button
                echo '<div class="receipt-buttons">';
                echo '<button class="print-btn" onclick="printReceipt()"><i class="fas fa-print"></i> Print Receipt</button>';
                echo '<button class="clear-btn" onclick="clearForm()"><i class="fas fa-redo"></i> New Reservation</button>';
                echo '</div>';
                
            } else {
                echo '<div class="empty-receipt">';
                echo '<i class="fas fa-receipt"></i>';
                echo '<p>Please fill out the reservation form and click "Reserve" to see your receipt here.</p>';
                echo '<p>The receipt will display the customer name, hotel details, room type, activities, and total charges.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    
    <script>
        // Show/hide activity hours based on checkbox selection
        document.addEventListener('DOMContentLoaded', function() {
            const activityCheckboxes = document.querySelectorAll('input[name="activities[]"]');
            
            activityCheckboxes.forEach(checkbox => {
                // Set initial state
                toggleActivityHours(checkbox);
                
                // Add event listener for changes
                checkbox.addEventListener('change', function() {
                    toggleActivityHours(this);
                });
            });
            
            function toggleActivityHours(checkbox) {
                const activityId = checkbox.id;
                const hoursInput = document.getElementById(activityId + '_hours');
                
                if (hoursInput) {
                    if (checkbox.checked) {
                        hoursInput.disabled = false;
                        hoursInput.value = hoursInput.value === '0' ? '1' : hoursInput.value;
                    } else {
                        hoursInput.disabled = true;
                        hoursInput.value = '0';
                    }
                }
            }
            
            // Set minimum date for check-out based on check-in date
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            
            if (checkInInput && checkOutInput) {
                // Set initial min date for check-out
                if (checkInInput.value) {
                    checkOutInput.min = checkInInput.value;
                }
                
                // Update min date when check-in changes
                checkInInput.addEventListener('change', function() {
                    checkOutInput.min = this.value;
                    
                    // If check-out is before new check-in, clear it
                    if (checkOutInput.value && checkOutInput.value < this.value) {
                        checkOutInput.value = '';
                    }
                });
            }
            
            // Set today as default for check-in if empty
            const today = new Date().toISOString().split('T')[0];
            if (checkInInput && !checkInInput.value) {
                checkInInput.value = today;
                checkInInput.min = today;
                
                // Set check-out to tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowStr = tomorrow.toISOString().split('T')[0];
                
                if (checkOutInput && !checkOutInput.value) {
                    checkOutInput.value = tomorrowStr;
                    checkOutInput.min = tomorrowStr;
                }
            }
            
            // Clear form button functionality
            const clearFormBtn = document.getElementById('clearFormBtn');
            if (clearFormBtn) {
                clearFormBtn.addEventListener('click', clearForm);
            }
        });
        
        // Function to print receipt
        function printReceipt() {
            window.print();
        }
        
        // Function to clear form and reset receipt
        function clearForm() {
            // Reset the form
            document.getElementById('reservationForm').reset();
            
            // Reset activity hours to 0 and disable them
            const activityHoursInputs = document.querySelectorAll('.activity-hours input');
            activityHoursInputs.forEach(input => {
                input.value = '0';
                input.disabled = true;
            });
            
            // Reset check-in and check-out dates to defaults
            const today = new Date().toISOString().split('T')[0];
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            document.getElementById('check_in').value = today;
            document.getElementById('check_out').value = tomorrowStr;
            document.getElementById('check_out').min = today;
            
            // Reset radio buttons to default (first option)
            const roomTypeRadios = document.getElementsByName('room_type');
            if (roomTypeRadios.length > 0) {
                roomTypeRadios[0].checked = true;
            }
            
            const boardTypeRadios = document.getElementsByName('board_type');
            if (boardTypeRadios.length > 0) {
                boardTypeRadios[0].checked = true;
            }
            
            // Reset hotel dropdown to first option
            document.getElementById('hotel').selectedIndex = 0;
            
            // Clear the receipt container
            const receiptContainer = document.querySelector('.receipt-container');
            if (receiptContainer) {
                receiptContainer.innerHTML = `
                    <h2>Reservation Receipt</h2>
                    <div class="empty-receipt">
                        <i class="fas fa-receipt"></i>
                        <p>Please fill out the reservation form and click "Reserve" to see your receipt here.</p>
                        <p>The receipt will display the customer name, hotel details, room type, activities, and total charges.</p>
                    </div>
                `;
            }
            
            // Show a confirmation message
            alert('Form cleared successfully! You can now enter new reservation details.');
        }
    </script>
</body>
</html>