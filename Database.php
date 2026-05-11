<?php

// Show errors (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Get form data
$name = trim($_POST['name'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$honeypot = $_POST['website'] ?? '';

// Validation
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

// Database credentials
$host = "localhost";
$username = "u563786655_tech";
$password = "Techstersol789@";
$dbname = "u563786655_tech";

// Connect DB
$con = new mysqli($host, $username, $password, $dbname);

if ($con->connect_error) {
    die("Database connection failed");
}

// Insert into database
$stmt = $con->prepare("INSERT INTO contactform (name, subject, email, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $subject, $email, $message);

if ($stmt->execute()) {

    $mail = new PHPMailer(true);

    try {

        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@techstersol.com';
        $mail->Password = 'Z1a@y3e4m789@';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender
        $mail->setFrom('info@techstersol.com', 'Techstersol');

        // Receiver
        $mail->addAddress('mzayemazam@gmail.com');

        // Email Content
        $mail->isHTML(false);
        $mail->Subject = "New Contact Form Submission";

        $mail->Body =
            "Name: $name\n\n" .
            "Subject: $subject\n\n" .
            "Email: $email\n\n" .
            "Message:\n$message";

        // Send Email
        $mail->send();

        header("Location: https://techstersol.com?success=1");
        exit;

    } catch (Exception $e) {

        header("Location: https://techstersol.com?error=1");
    }

} else {
        header("Location: https://techstersol.com?error=1");
}

$stmt->close();
$con->close();

?>