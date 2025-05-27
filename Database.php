<?php
// Sanitize and validate inputs
$name     = trim($_POST['name'] ?? '');
$subject  = trim($_POST['subject'] ?? '');
$email    = trim($_POST['email'] ?? '');
$message  = trim($_POST['message'] ?? '');
$honeypot = trim($_POST['website'] ?? ''); // hidden anti-bot field

// Honeypot bot check and basic validation
if (
    $honeypot !== '' ||
    empty($name) || strlen($name) < 2 ||
    empty($subject) || strlen($subject) < 3 ||
    empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    empty($message) || strlen($message) < 10 || preg_match('/^[\W_]+$/', $message) // block dots-only, emojis-only, etc.
) {
    header("Location: contact.html?error=1");
    exit;
}

// Database credentials
$host     = "localhost";
$username = "u563786655_tech";
$password = "Techstersol789@";
$dbname   = "u563786655_tech";

// Database connection
$con = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    header("Location: contact.html?error=1");
    exit;
}

// Use prepared statement to prevent SQL injection
$stmt = $con->prepare("INSERT INTO contactform (name, subject, email, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    header("Location: https://techstersol.com?error=1");
    exit;
}
$stmt->bind_param("ssss", $name, $subject, $email, $message);
$stmt->execute();
$stmt->close();
$con->close();

// Compose email
$email_from = 'zayem@techstersol.com';
$email_subject = "New Form Submission from Techstersol";
$email_body = "You have received a new message:\n\n" .
              "Name: $name\n" .
              "Subject: $subject\n" .
              "Email: $email\n\n" .
              "Message:\n$message";s

$to = "mzayemazam@gmail.com";
$headers = "From: $email_from\r\n";
$headers .= "Reply-To: $email\r\n";

// Send email
mail($to, $email_subject, $email_body, $headers);

// Redirect with success
header("Location: https://techstersol.com?success=1");
exit;
?>
