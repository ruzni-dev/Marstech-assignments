<?php
    session_start();
    if (!isset($_SESSION['form_data'])) {
        header("Location: index.php");
        exit();
    }
    $formData = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
?><!DOCTYPE html>
<html>
<head>
    <title>Application Submitted Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success-box { 
            background: #d4edda; 
            color: #155724; 
            padding: 20px; 
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin: 20px 0;
        }
        .data-box { 
            background: #f8f9fa; 
            padding: 15px; 
            border-left: 4px solid #007bff;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>🎉 Application Submitted Successfully!</h1>
    
    <div class="success-box">
        <h2>✓ Thank You for Your Application!</h2>
        <p>We have received your job application successfully. Our HR team will review your application and contact you soon.</p>
    </div>
    
    <h3>Application Summary:</h3>
    
    <div class="data-box">
        <strong>Full Name:</strong> <?php echo htmlspecialchars($formData['name'] ?? ''); ?>
    </div>
    
    <div class="data-box">
        <strong>Email:</strong> <?php echo htmlspecialchars($formData['email'] ?? ''); ?>
    </div>
    
    <div class="data-box">
        <strong>Contact Number:</strong> <?php echo htmlspecialchars($formData['contact'] ?? ''); ?>
    </div>
    
    <div class="data-box">
        <strong>Date of Birth:</strong> <?php echo htmlspecialchars($formData['dob'] ?? ''); ?>
    </div>
    
    <div class="data-box">
        <strong>Position Applied:</strong> <?php echo htmlspecialchars($formData['position'] ?? ''); ?>
    </div>
    
    <div class="data-box">
        <strong>Years of Experience:</strong> <?php echo htmlspecialchars($formData['experience'] ?? ''); ?>
    </div>
    
    <div class="data-box">
        <strong>LinkedIn Profile:</strong> 
        <a href="<?php echo htmlspecialchars($formData['linkedIn'] ?? ''); ?>" target="_blank">
            <?php echo htmlspecialchars($formData['linkedIn'] ?? ''); ?>
        </a>
    </div>
    
    <div class="data-box">
        <strong>Skills:</strong> <?php echo !empty($formData['skills']) ? implode(", ", $formData['skills']) : 'None selected'; ?>
    </div>
    
    <div class="data-box">
        <strong>Cover Letter:</strong><br>
        <?php echo nl2br(htmlspecialchars($formData['letter'] ?? '')); ?>
    </div>
    
    <p style="margin-top: 30px;">
        <a href="index.php">← Submit Another Application</a> | 
        <a href="javascript:window.print()">📄 Print This Page</a>
    </p>
</body>
</html>