<?php
require_once 'db_connect.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/contact.html');
    exit;
}

// ── Sanitize & validate inputs ──────────────────────────────────────────────

function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$full_name          = clean($_POST['full_name']          ?? '');
$email              = clean($_POST['email']              ?? '');
$phone              = clean($_POST['phone']              ?? '');
$current_education  = clean($_POST['current_education']  ?? '');
$preferred_country  = clean($_POST['preferred_country']  ?? '');
$preferred_faculty  = clean($_POST['preferred_faculty']  ?? '');
$study_level        = clean($_POST['study_level']        ?? '');
$english_score      = clean($_POST['english_score']      ?? '');
$budget             = clean($_POST['budget']             ?? '');
$message            = clean($_POST['message']            ?? '');

// Basic validation
$errors = [];

if (empty($full_name))         $errors[] = 'Full name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if (empty($phone))             $errors[] = 'Phone number is required.';
if (empty($current_education)) $errors[] = 'Current education is required.';
if (empty($preferred_country)) $errors[] = 'Preferred country is required.';
if (empty($preferred_faculty)) $errors[] = 'Preferred faculty is required.';
if (empty($study_level))       $errors[] = 'Study level is required.';

if (!empty($errors)) {
    // Go back with error message
    $errorMsg = urlencode(implode(' | ', $errors));
    header("Location: ../pages/contact.html?status=error&msg=$errorMsg");
    exit;
}

// ── Insert into database ─────────────────────────────────────────────────────

$conn = getConnection();

$stmt = $conn->prepare("
    INSERT INTO student_inquiries
        (full_name, email, phone, current_edu, preferred_country,
         preferred_faculty, study_level, english_score, budget, message)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    'ssssssssss',
    $full_name,
    $email,
    $phone,
    $current_education,
    $preferred_country,
    $preferred_faculty,
    $study_level,
    $english_score,
    $budget,
    $message
);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: ../pages/contact.html?status=success');
    exit;
} else {
    $stmt->close();
    $conn->close();
    header('Location: ../pages/contact.html?status=error&msg=Database+error+occurred.');
    exit;
}
?>