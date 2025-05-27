<?php
// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get and sanitize input
$name = trim($_POST['name'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$honeypot = $_POST['website'] ?? '';

// Check for honeypot (spam bots), empty fields, short values
if (
    $honeypot !== '' ||
    empty($name) || strlen($name) < 2 ||
    empty($subject) ||
    empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    empty($message) || strlen($message) < 5
) {
    header("Location: https://techstersol.com?error=1");
    exit;
}

// DB connection credentials
$host = "localhost";
$username = "u563786655_tech";
$password = "Techstersol789@";
$dbname = "u563786655_tech";

// Connect to DB
$con = new mysqli($host, $username, $password, $dbname);

if ($con->connect_error) {
    header("Location: https://techstersol.com?error=1");
    exit;
}

// Prepared statement to prevent SQL injection
$stmt = $con->prepare("INSERT INTO contactform (name, subject, email, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $subject, $email, $message);

if ($stmt->execute()) {
    // Email notification
    $email_from = 'admin@techstersol.com';
    $email_subject = "New Form submission from Techstersol";
    $email_body = "You have received a new message from $name.\n\nSubject: $subject\nEmail: $email\n\nMessage:\n$message";
    $to = "mzayemazam@gmail.com";
    $headers = "From: $email_from\r\nReply-To: $email\r\n";

    mail($to, $email_subject, $email_body, $headers);

    header("Location: https://techstersol.com?success=1");
} else {
    header("Location: https://techstersol.com?error=1");
}

$stmt->close();
$con->close();
exit;
?>
