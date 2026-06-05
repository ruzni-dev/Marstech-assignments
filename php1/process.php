<?php
function filterName($field = "") {
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if (filter_var($field, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-zA-Z\s]+$/')))) {
        return $field;
    } else {
        return FALSE;
    }
}

function filterId($field = "") {
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if (filter_var($field, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-zA-Z0-9\s]+$/')))) {
        return $field;
    } else {
        return FALSE;
    }
}

function filterEmail($field = "") {
    $field = filter_var(trim($field), FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return $field;
    } else {
        return FALSE;
    }
}

function filterString($field = "") {
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if (!empty($field)) {
        return $field;
    } else {
        return FALSE;
    }
}

function filterDate($field = "") {
    if (empty($field)) {
        return false;
    }

    try {
        $date = new DateTime($field);
        $today = new DateTime();

        if ($date < $today) {
            return $field;
        }

        return false;
    } catch (Exception $e) {
        return false;
    }
}

// Initialize variables
$name = $email = $productId = $purchaseDate = $issue = "";
$nameErr = $emailErr = $productIdErr = $purchaseDateErr = $issueErr = $productImgErr = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['name'])) {
        $nameErr = "Please enter your full name";
    } else {
        $name = filterName($_POST['name']);
        if ($name == FALSE) {
            $nameErr = "Please enter a valid full name";
        }
    }

    if (empty($_POST['email'])) {
        $emailErr = "Please enter your email";
    } else {
        $email = filterEmail($_POST['email']);
        if ($email == FALSE) {
            $emailErr = "Please enter a valid email";
        }
    }

    if (empty($_POST['productId'])) {
        $productIdErr = "Please enter your product name or ID";
    } else {
        $productId = filterId($_POST['productId']);
        if ($productId == FALSE) {
            $productIdErr = "Please enter a valid product name or ID";
        }
    }

    if (empty($_POST['purchaseDate'])) {
        $purchaseDateErr = "Please set your product purchase date";
    } else {
        $purchaseDate = filterDate($_POST['purchaseDate']);
        if ($purchaseDate === false) {
            $purchaseDateErr = "Please set a valid purchase date";
        }
    }

    if (empty($_POST['issue'])) {
        $issueErr = "Please describe your issue";
    } else {
        $issue = filterString($_POST['issue']);
        if ($issue == FALSE) {
            $issueErr = "Please describe a valid issue";
        }
    }

    if (empty($_FILES['productImg']['name'])) {
        $productImgErr = "Please upload your damaged product picture, format: .jpg file";
    } else {
        $fileTmp = $_FILES['productImg']['tmp_name'];
        $fileName = $_FILES['productImg']['name'];
        $fileType = mime_content_type($fileTmp);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (($fileType !== "image/jpeg" && $fileType !== "image/pjpeg") || $fileExt !== "jpg") {
            $productImgErr = "Only .jpg files are allowed";
        } else {
            $productImgErr = "";
        }
    }
    
    // If no errors, you can process the form data further
    if (empty($nameErr) && empty($emailErr) && empty($productIdErr) && 
        empty($purchaseDateErr) && empty($issueErr) && empty($productImgErr)) {
        // Process the form data (save to database, send email, etc.)
        // Then redirect to a success page or show success message
        header("Location: success.php");
        exit();
    }
}
?>