<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgb(218, 218, 218);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1.5rem;
        }
        
        .btn-back {
            background: rgb(0, 90, 224);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.35rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 1.5rem;
        }
        
        .btn-back:hover {
            background: rgb(0, 70, 200);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>Thank You!</h1>
        <p>Your complaint has been successfully submitted. We will review your information and contact you shortly.</p>
        <a href="index.php" class="btn-back">Submit Another Complaint</a>
    </div>
</body>
</html>