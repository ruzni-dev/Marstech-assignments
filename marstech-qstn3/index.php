<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Shop Invoice System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .invoice-form, .invoice-result {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="text"] {
            width: 95%;
            padding: 5px;
        }
        input[type="button"], input[type="submit"], input[type="reset"], button {
            padding: 8px 16px;
            margin: 5px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #f9f9f9;
        }
        button.add-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button.remove-btn {
            background-color: #f44336;
            color: white;
            border: none;
        }
        .shop-info {
            margin-bottom: 20px;
        }
        .shop-info td {
            border: none;
            padding: 5px;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
            margin: 5px 0;
            padding: 5px;
            border: 1px solid red;
            background-color: #ffe6e6;
            border-radius: 3px;
        }
        .success {
            color: green;
            font-weight: bold;
            margin: 5px 0;
            padding: 5px;
            border: 1px solid green;
            background-color: #e6ffe6;
            border-radius: 3px;
        }
        .field-error {
            color: red;
            font-size: 12px;
            margin-top: 2px;
        }
        .required {
            color: red;
        }
        .actions {
            margin: 10px 0;
            text-align: center;
        }
        .item-row {
            position: relative;
        }
        .table-container {
            overflow-x: auto;
            position: relative;
        }
        .validation-summary {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ABC Shop Invoice System</h1>
        
        <?php
        // Initialize variables
        $shop_name = $address = $contact = $email = "";
        $items = [];
        $discount = 0;
        $subtotal = 0;
        $final_total = 0;
        $item_count = 3; // Default number of items
        
        // Validation arrays
        $errors = [];
        $field_errors = [
            'shop_name' => '',
            'address' => '',
            'contact' => '',
            'email' => ''
        ];
        
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            // Get shop information
            $shop_name = isset($_POST['shop_name']) ? trim(htmlspecialchars($_POST['shop_name'])) : "";
            $address = isset($_POST['address']) ? trim(htmlspecialchars($_POST['address'])) : "";
            $contact = isset($_POST['contact']) ? trim(htmlspecialchars($_POST['contact'])) : "";
            $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : "";
            
            // Validate shop name
            if (empty($shop_name)) {
                $errors[] = "Shop Name is required.";
                $field_errors['shop_name'] = "Please enter the shop name.";
            }
            
            // Validate address
            if (empty($address)) {
                $errors[] = "Address is required.";
                $field_errors['address'] = "Please enter the shop address.";
            }
            
            // Validate contact number
            if (empty($contact)) {
                $errors[] = "Contact Number is required.";
                $field_errors['contact'] = "Please enter the contact number.";
            } elseif (!preg_match('/^[0-9\-\+\(\)\s]{10,15}$/', $contact)) {
                $errors[] = "Contact Number format is invalid.";
                $field_errors['contact'] = "Please enter a valid contact number (10-15 digits, can include +, -, (), spaces).";
            }
            
            // Validate email
            if (empty($email)) {
                $errors[] = "Email Address is required.";
                $field_errors['email'] = "Please enter the email address.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email format is invalid.";
                $field_errors['email'] = "Please enter a valid email address.";
            }
            
            // Get item count from hidden field
            $item_count = isset($_POST['item_count']) ? intval($_POST['item_count']) : 3;
            
            // Process items
            $has_items = false;
            $item_errors = [];
            
            for ($i = 1; $i <= $item_count; $i++) {
                $item_code = isset($_POST["item_code_$i"]) ? trim($_POST["item_code_$i"]) : "";
                $item_name = isset($_POST["item_name_$i"]) ? trim($_POST["item_name_$i"]) : "";
                $quantity = isset($_POST["quantity_$i"]) ? $_POST["quantity_$i"] : "";
                $unit_price = isset($_POST["unit_price_$i"]) ? $_POST["unit_price_$i"] : "";
                
                // Check if at least one field in the row is filled
                if (!empty($item_code) || !empty($item_name) || !empty($quantity) || !empty($unit_price)) {
                    // Validate that all fields in the row are filled
                    if (empty($item_code)) {
                        $item_errors[] = "Item Code is required for item $i.";
                    }
                    if (empty($item_name)) {
                        $item_errors[] = "Item Name is required for item $i.";
                    }
                    if (empty($quantity)) {
                        $item_errors[] = "Quantity is required for item $i.";
                    } elseif (!is_numeric($quantity) || $quantity <= 0) {
                        $item_errors[] = "Quantity must be a positive number for item $i.";
                    }
                    if (empty($unit_price)) {
                        $item_errors[] = "Unit Price is required for item $i.";
                    } elseif (!is_numeric($unit_price) || $unit_price <= 0) {
                        $item_errors[] = "Unit Price must be a positive number for item $i.";
                    }
                    
                    // If all fields are valid, process the item
                    if (!empty($item_code) && !empty($item_name) && is_numeric($quantity) && $quantity > 0 && is_numeric($unit_price) && $unit_price > 0) {
                        $quantity = intval($quantity);
                        $unit_price = floatval($unit_price);
                        $total_price = $quantity * $unit_price;
                        $item_discount = 0;
                        
                        // Calculate discount based on quantity
                        if ($quantity > 50) {
                            // For quantities > 50: 5 items free for each 30 items
                            $free_items = floor($quantity / 30) * 5;
                            $item_discount = $free_items * $unit_price;
                        } elseif ($quantity > 20) {
                            // For quantities > 20: 10% discount
                            $item_discount = $total_price * 0.10;
                        } elseif ($quantity > 10) {
                            // For quantities > 10: 2% discount
                            $item_discount = $total_price * 0.02;
                        }
                        
                        $item_total = $total_price - $item_discount;
                        
                        $items[] = [
                            'code' => $item_code,
                            'name' => $item_name,
                            'quantity' => $quantity,
                            'unit_price' => $unit_price,
                            'total_price' => $total_price,
                            'discount' => $item_discount,
                            'item_total' => $item_total
                        ];
                        
                        $subtotal += $total_price;
                        $discount += $item_discount;
                        $has_items = true;
                    }
                }
            }
            
            // Check if no items were added
            if (!$has_items) {
                $errors[] = "At least one valid item is required to generate an invoice.";
                $item_errors[] = "Please fill in at least one complete item row.";
            }
            
            // Add item errors to main errors array
            $errors = array_merge($errors, $item_errors);
            
            $final_total = $subtotal - $discount;
            
            // Display invoice if no errors
            if (empty($errors) && $has_items) {
                echo '<div class="invoice-result">';
                echo '<h2>ABC Shop - Invoice</h2>';
                echo '<div class="shop-info">';
                echo '<p><strong>Shop Name:</strong> ' . $shop_name . '</p>';
                echo '<p><strong>Address:</strong> ' . $address . '</p>';
                echo '<p><strong>Contact Number:</strong> ' . $contact . '</p>';
                echo '<p><strong>Email:</strong> ' . $email . '</p>';
                echo '</div>';
                
                echo '<table>';
                echo '<tr>';
                echo '<th>Item Code</th>';
                echo '<th>Item Name</th>';
                echo '<th>Quantity</th>';
                echo '<th>Unit Price (Rs:)</th>';
                echo '<th>Total Price (Rs:)</th>';
                echo '</tr>';
                
                foreach ($items as $item) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($item['code']) . '</td>';
                    echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                    echo '<td>' . $item['quantity'] . '</td>';
                    echo '<td>' . number_format($item['unit_price'], 2) . '</td>';
                    echo '<td>' . number_format($item['total_price'], 2) . '</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
                
                echo '<div class="total-section">';
                echo '<p><strong>Subtotal:</strong> Rs. ' . number_format($subtotal, 2) . '</p>';
                echo '<p><strong>Discount:</strong> Rs. ' . number_format($discount, 2) . '</p>';
                echo '<p><strong>Total:</strong> Rs. ' . number_format($final_total, 2) . '</p>';
                echo '</div>';
                
                echo '<div class="actions">';
                echo '<p><a href="' . $_SERVER['PHP_SELF'] . '">Create New Invoice</a></p>';
                echo '</div>';
                echo '</div>';
            } else {
                // Display validation errors
                echo '<div class="validation-summary">';
                echo '<h3>Please correct the following errors:</h3>';
                echo '<ul>';
                foreach ($errors as $error) {
                    echo '<li class="error">' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }
        }
        
        // Display form if not submitted or if there were errors
        if (!isset($_POST['submit']) || (isset($_POST['submit']) && !empty($errors))) {
        ?>
        
        <div class="invoice-form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="invoiceForm" onsubmit="return validateForm()">
                <input type="hidden" name="item_count" id="item_count" value="<?php echo $item_count; ?>">
                
                <h3>Shop Information <span class="required">*</span></h3>
                <table class="shop-info">
                    <tr>
                        <td>Shop name: <span class="required">*</span></td>
                        <td>
                            <input type="text" name="shop_name" id="shop_name" value="<?php echo htmlspecialchars($shop_name); ?>" required>
                            <?php if (!empty($field_errors['shop_name'])): ?>
                                <div class="field-error"><?php echo htmlspecialchars($field_errors['shop_name']); ?></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Address: <span class="required">*</span></td>
                        <td>
                            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($address); ?>" required>
                            <?php if (!empty($field_errors['address'])): ?>
                                <div class="field-error"><?php echo htmlspecialchars($field_errors['address']); ?></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Contact Number: <span class="required">*</span></td>
                        <td>
                            <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
                            <?php if (!empty($field_errors['contact'])): ?>
                                <div class="field-error"><?php echo htmlspecialchars($field_errors['contact']); ?></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Email: <span class="required">*</span></td>
                        <td>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            <?php if (!empty($field_errors['email'])): ?>
                                <div class="field-error"><?php echo htmlspecialchars($field_errors['email']); ?></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                
                <h3>Items Information <span class="required">*</span></h3>
                <p><em>Fill in at least one complete item row. All fields in a row must be filled if you start entering data.</em></p>
                
                <div class="table-container">
                    <table id="itemsTable">
                        <thead>
                            <tr>
                                <th>Item Code <span class="required">*</span></th>
                                <th>Item Name <span class="required">*</span></th>
                                <th>Quantity <span class="required">*</span></th>
                                <th>Unit Price (Rs:) <span class="required">*</span></th>
                                <th style="width: 50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <?php
                            // Display initial item rows
                            for ($i = 1; $i <= $item_count; $i++) {
                                $item_code_val = isset($_POST["item_code_$i"]) ? htmlspecialchars($_POST["item_code_$i"]) : '';
                                $item_name_val = isset($_POST["item_name_$i"]) ? htmlspecialchars($_POST["item_name_$i"]) : '';
                                $quantity_val = isset($_POST["quantity_$i"]) ? htmlspecialchars($_POST["quantity_$i"]) : '';
                                $unit_price_val = isset($_POST["unit_price_$i"]) ? htmlspecialchars($_POST["unit_price_$i"]) : '';
                                
                                echo '<tr class="item-row" id="itemRow_' . $i . '">';
                                echo '<td><input type="text" name="item_code_' . $i . '" id="item_code_' . $i . '" value="' . $item_code_val . '" placeholder="e.g., ABC001"></td>';
                                echo '<td><input type="text" name="item_name_' . $i . '" id="item_name_' . $i . '" value="' . $item_name_val . '" placeholder="e.g., Yoghurt"></td>';
                                echo '<td><input type="text" name="quantity_' . $i . '" id="quantity_' . $i . '" value="' . $quantity_val . '" placeholder="e.g., 10"></td>';
                                echo '<td><input type="text" name="unit_price_' . $i . '" id="unit_price_' . $i . '" value="' . $unit_price_val . '" placeholder="e.g., 40.00"></td>';
                                echo '<td>';
                                if ($i > 1) {
                                    echo '<button type="button" class="remove-btn" onclick="removeItemRow(' . $i . ')">-</button>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="actions">
                    <button type="button" class="add-btn" onclick="addItemRow()">+ Add Item</button>
                </div>
                
                <div class="actions">
                    <input type="submit" name="submit" value="Submit">
                    <input type="reset" value="Clear" onclick="clearForm()">
                </div>
            </form>
        </div>
        
        <?php } ?>
    </div>
    
    <script>
        let itemCounter = <?php echo $item_count; ?>;
        
        function addItemRow() {
            itemCounter++;
            
            // Update the hidden field with new item count
            document.getElementById('item_count').value = itemCounter;
            
            // Create new row
            const tableBody = document.getElementById('itemsTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.id = 'itemRow_' + itemCounter;
            
            newRow.innerHTML = `
                <td><input type="text" name="item_code_${itemCounter}" id="item_code_${itemCounter}" placeholder="e.g., ABC001"></td>
                <td><input type="text" name="item_name_${itemCounter}" id="item_name_${itemCounter}" placeholder="e.g., Yoghurt"></td>
                <td><input type="text" name="quantity_${itemCounter}" id="quantity_${itemCounter}" placeholder="e.g., 10"></td>
                <td><input type="text" name="unit_price_${itemCounter}" id="unit_price_${itemCounter}" placeholder="e.g., 40.00"></td>
                <td><button type="button" class="remove-btn" onclick="removeItemRow(${itemCounter})">-</button></td>
            `;
            
            tableBody.appendChild(newRow);
            
            // Scroll to the newly added row
            newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        function removeItemRow(rowId) {
            const row = document.getElementById('itemRow_' + rowId);
            if (row) {
                row.remove();
                // Update item counter if removing the last row
                updateItemCount();
            }
        }
        
        function updateItemCount() {
            // Count remaining rows
            const rows = document.querySelectorAll('.item-row');
            itemCounter = rows.length;
            document.getElementById('item_count').value = itemCounter;
            
            // Update row IDs and button onclick handlers
            rows.forEach((row, index) => {
                const newId = index + 1;
                row.id = 'itemRow_' + newId;
                
                // Update inputs names and ids
                const inputs = row.querySelectorAll('input');
                inputs[0].name = 'item_code_' + newId;
                inputs[0].id = 'item_code_' + newId;
                inputs[1].name = 'item_name_' + newId;
                inputs[1].id = 'item_name_' + newId;
                inputs[2].name = 'quantity_' + newId;
                inputs[2].id = 'quantity_' + newId;
                inputs[3].name = 'unit_price_' + newId;
                inputs[3].id = 'unit_price_' + newId;
                
                // Update remove button onclick
                const removeBtn = row.querySelector('.remove-btn');
                if (removeBtn) {
                    removeBtn.onclick = function() { removeItemRow(newId); };
                }
                
                // Show/hide remove button (hide for first row)
                const lastCell = row.cells[4];
                if (lastCell) {
                    if (newId === 1) {
                        lastCell.innerHTML = '';
                    } else if (!lastCell.querySelector('.remove-btn')) {
                        lastCell.innerHTML = `<button type="button" class="remove-btn" onclick="removeItemRow(${newId})">-</button>`;
                    }
                }
            });
        }
        
        function clearForm() {
            // Clear all form fields
            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.value = '';
            });
            
            // Clear any existing error messages
            document.querySelectorAll('.field-error').forEach(error => {
                error.remove();
            });
            
            // Reset to 3 items only
            const tableBody = document.getElementById('itemsTableBody');
            tableBody.innerHTML = '';
            
            for (let i = 1; i <= 3; i++) {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.id = 'itemRow_' + i;
                
                newRow.innerHTML = `
                    <td><input type="text" name="item_code_${i}" id="item_code_${i}" placeholder="e.g., ABC001"></td>
                    <td><input type="text" name="item_name_${i}" id="item_name_${i}" placeholder="e.g., Yoghurt"></td>
                    <td><input type="text" name="quantity_${i}" id="quantity_${i}" placeholder="e.g., 10"></td>
                    <td><input type="text" name="unit_price_${i}" id="unit_price_${i}" placeholder="e.g., 40.00"></td>
                    <td>${i > 1 ? '<button type="button" class="remove-btn" onclick="removeItemRow(' + i + ')">-</button>' : ''}</td>
                `;
                
                tableBody.appendChild(newRow);
            }
            
            itemCounter = 3;
            document.getElementById('item_count').value = 3;
        }
        
        // Client-side validation function
        function validateForm() {
            let isValid = true;
            
            // Clear previous error messages
            document.querySelectorAll('.field-error').forEach(error => {
                error.remove();
            });
            
            // Validate shop name
            const shopName = document.getElementById('shop_name').value.trim();
            if (!shopName) {
                showFieldError('shop_name', 'Shop Name is required.');
                isValid = false;
            }
            
            // Validate address
            const address = document.getElementById('address').value.trim();
            if (!address) {
                showFieldError('address', 'Address is required.');
                isValid = false;
            }
            
            // Validate contact number
            const contact = document.getElementById('contact').value.trim();
            if (!contact) {
                showFieldError('contact', 'Contact Number is required.');
                isValid = false;
            } else if (!/^[0-9\-\+\(\)\s]{10,15}$/.test(contact)) {
                showFieldError('contact', 'Please enter a valid contact number (10-15 digits, can include +, -, (), spaces).');
                isValid = false;
            }
            
            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                showFieldError('email', 'Email Address is required.');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                showFieldError('email', 'Please enter a valid email address.');
                isValid = false;
            }
            
            // Validate items - at least one complete row must be filled
            let hasValidItem = false;
            const itemRows = document.querySelectorAll('.item-row');
            
            itemRows.forEach((row, index) => {
                const rowNum = index + 1;
                const itemCode = document.getElementById('item_code_' + rowNum).value.trim();
                const itemName = document.getElementById('item_name_' + rowNum).value.trim();
                const quantity = document.getElementById('quantity_' + rowNum).value.trim();
                const unitPrice = document.getElementById('unit_price_' + rowNum).value.trim();
                
                // Check if any field in this row has data
                if (itemCode || itemName || quantity || unitPrice) {
                    // If any field is filled, all must be filled and valid
                    let rowValid = true;
                    
                    if (!itemCode) {
                        showFieldError('item_code_' + rowNum, 'Item Code is required for item ' + rowNum + '.');
                        rowValid = false;
                    }
                    
                    if (!itemName) {
                        showFieldError('item_name_' + rowNum, 'Item Name is required for item ' + rowNum + '.');
                        rowValid = false;
                    }
                    
                    if (!quantity) {
                        showFieldError('quantity_' + rowNum, 'Quantity is required for item ' + rowNum + '.');
                        rowValid = false;
                    } else if (!/^\d+$/.test(quantity) || parseInt(quantity) <= 0) {
                        showFieldError('quantity_' + rowNum, 'Quantity must be a positive number for item ' + rowNum + '.');
                        rowValid = false;
                    }
                    
                    if (!unitPrice) {
                        showFieldError('unit_price_' + rowNum, 'Unit Price is required for item ' + rowNum + '.');
                        rowValid = false;
                    } else if (!/^\d+(\.\d{1,2})?$/.test(unitPrice) || parseFloat(unitPrice) <= 0) {
                        showFieldError('unit_price_' + rowNum, 'Unit Price must be a positive number for item ' + rowNum + '.');
                        rowValid = false;
                    }
                    
                    if (rowValid) {
                        hasValidItem = true;
                    } else {
                        isValid = false;
                    }
                }
            });
            
            if (!hasValidItem) {
                alert('At least one complete item row must be filled.');
                isValid = false;
            }
            
            return isValid;
        }
        
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            if (field) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            }
        }
        
        // Real-time validation as user types
        document.addEventListener('DOMContentLoaded', function() {
            // Add input event listeners for real-time validation
            const form = document.getElementById('invoiceForm');
            if (form) {
                // Shop name validation
                const shopNameField = document.getElementById('shop_name');
                if (shopNameField) {
                    shopNameField.addEventListener('blur', function() {
                        validateShopName();
                    });
                }
                
                // Address validation
                const addressField = document.getElementById('address');
                if (addressField) {
                    addressField.addEventListener('blur', function() {
                        validateAddress();
                    });
                }
                
                // Contact validation
                const contactField = document.getElementById('contact');
                if (contactField) {
                    contactField.addEventListener('blur', function() {
                        validateContact();
                    });
                }
                
                // Email validation
                const emailField = document.getElementById('email');
                if (emailField) {
                    emailField.addEventListener('blur', function() {
                        validateEmail();
                    });
                }
            }
        });
        
        // Individual validation functions
        function validateShopName() {
            const field = document.getElementById('shop_name');
            const value = field.value.trim();
            removeFieldError('shop_name');
            
            if (!value) {
                showFieldError('shop_name', 'Shop Name is required.');
                return false;
            }
            return true;
        }
        
        function validateAddress() {
            const field = document.getElementById('address');
            const value = field.value.trim();
            removeFieldError('address');
            
            if (!value) {
                showFieldError('address', 'Address is required.');
                return false;
            }
            return true;
        }
        
        function validateContact() {
            const field = document.getElementById('contact');
            const value = field.value.trim();
            removeFieldError('contact');
            
            if (!value) {
                showFieldError('contact', 'Contact Number is required.');
                return false;
            } else if (!/^[0-9\-\+\(\)\s]{10,15}$/.test(value)) {
                showFieldError('contact', 'Please enter a valid contact number (10-15 digits, can include +, -, (), spaces).');
                return false;
            }
            return true;
        }
        
        function validateEmail() {
            const field = document.getElementById('email');
            const value = field.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            removeFieldError('email');
            
            if (!value) {
                showFieldError('email', 'Email Address is required.');
                return false;
            } else if (!emailRegex.test(value)) {
                showFieldError('email', 'Please enter a valid email address.');
                return false;
            }
            return true;
        }
        
        function removeFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                const errorDiv = field.parentNode.querySelector('.field-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        }
    </script>
</body>
</html>