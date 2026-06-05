<?php
    session_start();

    $resumeOut = "";
    $name = $email = $contact = $dob = $position  = $resume = $letter = $linkedIn = $experience = "";
    $nameErr = $emailErr = $contactErr = $dobErr = $positionErr = $resumeErr = $letterErr = $linkedInErr = $experienceErr = $skillsErr = "";
    $minAge = 18;
    $selectedSkills = [];

    function filterEmail($email) {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    function filterContact($contact = "") {
        $contact = trim($contact);
        $digits = preg_replace('/[^\d]/', '', $contact);
        if (strlen($digits) >= 7 && strlen($digits) <= 15) {
            return $digits;
        }
        return FALSE;
    }

    function validateDOB($dob, $minAge = 13) {
        $dob = trim($dob);
        if (empty($dob)) {
            return false;
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
            return false;
        }
        list($year, $month, $day) = explode('-', $dob);
        if (!checkdate($month, $day, $year)) {
            return false;
        }
        $dobDate = strtotime($dob);
        if ($dobDate > time()) {
            return false;
        }
        $age = date('Y') - $year;
        if (date('md') < date('md', $dobDate)) {
            $age--;
        }
        if ($age < $minAge) {
            return false;
        }
        return $dob;
    }

    $validPositions = [
        'Software Developer' => 'Software Developer',
        'Web Designer' => 'Web Designer', 
        'Projrct Manager' => 'Project Manager',
    ];

    function validateLinkedIn($linkedIn) {
        $linkedIn = trim($linkedIn);
        if (empty($linkedIn)) {
            return false;
        }
        if (!preg_match('/^https?:\/\//i', $linkedIn)) {
            $linkedIn = 'https://' . $linkedIn;
        }
        if (!filter_var($linkedIn, FILTER_VALIDATE_URL)) {
            return false;
        }
        if (stripos($linkedIn, 'linkedin.com/in/') === false) {
            return false;
        }
        return $linkedIn;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if (empty($_POST['name'])) {
            $nameErr = "Please enter your full name";
        } else {
            $name = $_POST['name'];
        }

        $raw_email = $_POST['email'] ?? '';
        if (empty($raw_email)) {
            $emailErr = "Please enter your email.";
            $email = $raw_email;
        } else {
            $validated_email = filterEmail($raw_email);
            if ($validated_email === false) {
                $emailErr = "Please enter a valid email address";
                $email = $raw_email;
            } else {
                $email = $validated_email;
            }
        }

        if (empty($_POST['contact'])) {
            $contactErr = "Please enter your contact number";
        } else {
            $filteredContact = filterContact($_POST['contact']);
            if ($filteredContact === FALSE) {
                $contactErr = "Please enter a valid contact number";
                $contact = $_POST['contact'];
            } else {
                $contact = $filteredContact;
            }
        }

        $raw_dob = $_POST['dob'] ?? '';
        if (empty($raw_dob)) {
            $dobErr = "Please enter your date of birth.";
            $dob = $raw_dob;
        } else {
            $validated_dob = validateDOB($raw_dob, $minAge);
            if ($validated_dob === false) {
                $dobErr = "Please enter a valid date of birth. Minimum age is $minAge years.";
                $dob = $raw_dob;
            } else {
                $dob = $validated_dob;
            }
        }

        if (empty($_POST['position'])) {
            $positionErr = "Select your position.";
        } else {
            $selected = $_POST['position'];
            $position = $selected;
        }

        if (isset($_FILES['resume'])) {
            $file = $_FILES['resume'];
            if ($file['error'] !== 0) {
                $resumeErr = "Error uploading resume.";
            } else {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if ($ext !== 'pdf') {
                    $resumeErr = "Only PDF files allowed";
                } else {
                    if (!is_dir('uploads')) {
                        mkdir('uploads', 0777, true);
                    }
                    move_uploaded_file($file['tmp_name'], 'uploads/' . $file['name']);
                    $resumeOut = $file['name'];
                }
            }
        } else {
            $resumeErr = "Please select a file";
        }

        if (empty(trim($_POST['letter']))) {
            $letterErr = "Please describe your cover letter";
        } else {
            $letter = htmlspecialchars(trim($_POST['letter']));
        }

        $raw_linkedIn = $_POST['linkedIn'] ?? '';
        if (empty(trim($raw_linkedIn))) {
            $linkedInErr = "Please enter your LinkedIn profile URL";
            $linkedIn = $raw_linkedIn;
        } else {
            $validated_linkedIn = validateLinkedIn($raw_linkedIn);
            if ($validated_linkedIn === false) {
                $linkedInErr = "Please enter a valid LinkedIn profile URL (format: linkedin.com/in/username)";
                $linkedIn = $raw_linkedIn;
            } else {
                $linkedIn = $validated_linkedIn;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST['experience'])) {
                $experienceErr = "Please enter years of experience";
            } else {
                $experience = (int)$_POST['experience'];
                if ($experience < 0 || $experience > 50) {
                    $experienceErr = "Please enter a valid number of years (0-50)";
                }
            }
        }

        $selectedSkills = $_POST['skills'] ?? [];
        if (empty($selectedSkills)) {
            $skillsErr = "Please select at least one skill";
        }

        if (empty($nameErr) && empty($emailErr) && empty($contactErr) && 
            empty($dobErr) && empty($positionErr) && empty($resumeErr) && 
            empty($letterErr) && empty($linkedInErr) && empty($experienceErr) && 
            empty($skillsErr)) {
            $_SESSION['form_data'] = [
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'dob' => $dob,
                'position' => $position,
                'letter' => $letter,
                'linkedIn' => $linkedIn,
                'experience' => $experience,
                'skills' => $selectedSkills
            ];
            header("Location: success.php");
            exit();
        }
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Aplication Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Job Aplication Form</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Full Name :</label>
                <input type="text" class="cls1 form-control <?php echo !empty($nameErr) ? 'error' : 'success'; ?>" name="name" id="name" placeholder="John Doe" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $nameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="text" class="cls1 form-control <?php echo !empty($emailErr) ? 'error' : 'success'; ?>" name="email" id="email" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number :</label>
                <input type="number" class="cls1 form-control <?php echo !empty($contactErr) ? 'error' : 'success'; ?>" name="contact" id="contact" placeholder="e.g., 012-345 6789 or +60 12-345 6789" value="<?php echo htmlspecialchars($contact); ?>">
                <span class="error"><?php echo $contactErr; ?></span>
            </div>
            <div class="form-group">
                <label for="dob">Date Of Birth :</label>
                <input type="date" class="cls1 form-control <?php echo !empty($dobErr) ? 'error' : 'success'; ?>" name="dob" id="dob" value="<?php echo htmlspecialchars($dob); ?>">
                <span class="error"><?php echo $dobErr; ?></span>
            </div>
            <div class="form-group">
                <label for="position">Position Applied For :</label>
                <select name="position" id="position" class="form-control cls1 <?php echo !empty($positionErr) ? 'error' : 'success'; ?>">
                    <option value="">-- Select a Position --</option>
                    <?php foreach ($validPositions as $value => $label): ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"
                            <?php echo ($position == $value) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error"><?php echo $positionErr; ?></span>
            </div>
            <div class="form-group">
                <label for="resume">Upload your resume PDF :</label>
                <input type="file" name="resume" class="cls1 form-control" id="resume">
                <span class="error"><?php echo $resumeErr; ?></span>
                <span class="success"><?php echo $resumeOut; ?></span>
            </div>
            <div class="form-group">
                <label for="letter">Cover Letter :</label>
                <textarea rows="5" class="cls1 form-control <?php echo !empty($letterErr) ? 'error' : 'success'; ?>" name="letter" id="letter"><?php echo $letter; ?></textarea>
                <span class="error"><?php echo $letterErr; ?></span>    
            </div>
            <div class="form-group">
                <label for="linkedIn">LinkedIn Profile Url :</label>
                <input type="text" class="cls1 form-control <?php echo !empty($linkedInErr) ? 'error' : 'success'; ?>" name="linkedIn" id="linkedIn" placeholder="www.linkedin.com/in/username" value="<?php echo htmlspecialchars($linkedIn); ?>">
                <span class="error"><?php echo $linkedInErr; ?></span>
            </div>
            <div class="form-group">
                <label for="experience">Work Experience Years :</label>
                <input type="number" class="cls1 form-control <?php echo !empty($linkedInErr) ? 'error' : 'success'; ?>" name="experience" id="experience" placeholder="0->50" value="<?php echo htmlspecialchars($experience); ?>">
                <span class="error"><?php echo $experienceErr; ?></span>
            </div>
            <div class="form-group">
                <label for="skill">Skills :</label>
                <div class="cls1 checkbox form-control">
                    <input type="checkbox" name="skills[]" value="html" id="html" <?php echo in_array('html', $selectedSkills) ? 'checked' : ''; ?>><label for="html">HTML</label>
                    <input type="checkbox" name="skills[]" value="css" id="css" <?php echo in_array('css', $selectedSkills) ? 'checked' : ''; ?>><label for="css">CSS</label>
                    <input type="checkbox" name="skills[]" value="js" id="js" <?php echo in_array('js', $selectedSkills) ? 'checked' : ''; ?>><label for="js">JavaScript</label>
                    <input type="checkbox" name="skills[]" value="php" id="php" <?php echo in_array('php', $selectedSkills) ? 'checked' : ''; ?>><label for="php">PHP</label>
                    <input type="checkbox" name="skills[]" value="java" id="java" <?php echo in_array('java', $selectedSkills) ? 'checked' : ''; ?>><label for="java">Java</label>
                </div>
                <span class="error"><?php echo $skillsErr; ?></span>
            </div>
            <input type="submit" value="Apply" class="submit" name="submit">
        </form>
    </div>
</body>
</html>