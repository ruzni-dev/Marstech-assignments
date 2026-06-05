<?php
// Include the validation logic
require_once 'process.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Complaint Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgb(218, 218, 218);
            padding: 20px;
        }

        .container {
            background: white;
            padding: 2rem;
            margin: 2rem auto;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }

        .success {
            color: green;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .submit-btn {
            background: rgb(0, 90, 224);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.35rem;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: rgb(0, 70, 200);
        }
        
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Complaint Form</h1>
        
        <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($nameErr) && empty($emailErr) && empty($productIdErr) && 
                empty($purchaseDateErr) && empty($issueErr) && empty($productImgErr)): ?>
            <div class="alert alert-success">
                Thank you for your submission! We'll contact you shortly.
            </div>
        <?php endif; ?>
        
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Full Name: <span class="required">*</span></label>
                <input class="form-control" type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $nameErr; ?></span>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address: <span class="required">*</span></label>
                <input class="form-control" type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            
            <div class="form-group">
                <label for="productId">Product Name or ID: <span class="required">*</span></label>
                <input class="form-control" type="text" name="productId" id="productId" value="<?php echo htmlspecialchars($productId); ?>">
                <span class="error"><?php echo $productIdErr; ?></span>
            </div>
            
            <div class="form-group">
                <label for="purchaseDate">Date of Purchase: <span class="required">*</span></label>
                <input class="form-control" type="date" name="purchaseDate" id="purchaseDate" value="<?php echo htmlspecialchars($purchaseDate); ?>">
                <span class="error"><?php echo $purchaseDateErr; ?></span>
            </div>
            
            <div class="form-group">
                <label for="issue">Describe the issue: <span class="required">*</span></label>
                <textarea class="form-control" name="issue" id="issue" rows="5"><?php echo htmlspecialchars($issue); ?></textarea>
                <span class="error"><?php echo $issueErr; ?></span>
            </div>
            
            <div class="form-group">
                <label for="productImg">Upload Picture of Damaged Product: <span class="required">*</span></label>
                <input class="form-control" type="file" name="productImg" id="productImg" accept=".jpg,.jpeg">
                <small class="form-text text-muted">Only JPG files are accepted</small>
                <span class="error"><?php echo $productImgErr; ?></span>
            </div>
            
            <button type="submit" class="submit-btn" name="submit">Submit Complaint</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>