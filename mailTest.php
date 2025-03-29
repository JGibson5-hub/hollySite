<?php
$to = 'heyjudegibson@gmail.com';
$subject = 'Test Mail';
$message = 'This is a test email sent by PHP script.';
$headers = 'From: holly@imaginutive.com' . "\r\n" . // Replace with your "from" email address
'Reply-To: holly@imaginutive.com' . "\r\n" . // Replace with your "reply-to" email address
'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers)){
echo "Test email sent successfully.";
}else{
echo "Failed to send test email.";
}
?>