<?php
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $alert = "";
    
$host = "localhost";
$username = "utopiant_tech";
$password = "Techstersol789@";
$dbname = "utopiant_tech";

$con = mysqli_connect($host, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO contactform (name, subject, email, message) VALUES ('$name', '$subject', '$email', '$message')";

$rs = mysqli_query($con, $sql);
/* if ($rs) {
    echo "Submit Successfully!";
} */

     

mysqli_close($con);

/*email */

$email_from = 'admin@techstersol.com';

$email_subject = "New Form submission from Techstersol";

$email_body = "You have received a new message from the user $name.\n"."Subject: $subject.\n"."Email:$email.\n"."Here is the message:\n $message";

#notification

 $to = "mzayemazam@gmail.com";

  $headers = "From: $email_from \r\n";

  $headers .= "Reply-To: $email \r\n";

  mail($to,$email_subject,$email_body,$headers);
  
#secure from injector

function IsInjected($str)
{
    $injections = array('(\n+)',
           '(\r+)',
           '(\t+)',
           '(%0A+)',
           '(%0D+)',
           '(%08+)',
           '(%09+)'
           );
               
    $inject = join('|', $injections);
    $inject = "/$inject/i";
    
    if(preg_match($inject,$str))
    {
      return true;
    }
    else
    {
      return false;
    }
}

if(IsInjected($email))
{
    echo "Bad email value!";
    exit;
}
header('Location: https://techstersol.com');

?>
