<?php
header('Content-Type: application/json');
$response = array('success' => false, 'message' => '');

try {
    // Verify request method
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method");
    }

    // Verify CSRF token if needed
    // session_start();
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     throw new Exception("Invalid security token");
    // }

    // Required fields
    $required_fields = ['name', 'email', 'message'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("All fields are required");
        }
    }

    // Sanitize and validate input
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $name = strip_tags($name);
    if (strlen($name) > 50) {
        throw new Exception("Name is too long");
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);
    $message = strip_tags($message);
    if (strlen($message) > 3000) {
        throw new Exception("Message is too long");
    }

    // Your email address
    $recipient = "holly@imaginuitive.com";

    // Set email subject with sanitized name
    $subject = "New Contact Form Message from " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    // Build email content with sanitized data
    $email_content = "Name: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "\n";
    $email_content .= "Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "\n\n";
    $email_content .= "Message:\n" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "\n";

    // Build email headers with proper formatting
    $email_headers = array(
        'From' => $recipient, // Using your own domain email for better deliverability
        'Reply-To' => $email,
        'X-Mailer' => 'PHP/' . phpversion(),
        'Content-Type' => 'text/plain; charset=UTF-8'
    );

    // Send the email
    if (mail($recipient, $subject, $email_content, implode("\r\n", array_map(function($v, $k) {
        return "$k: $v";
    }, $email_headers, array_keys($email_headers))))) {
        $response['success'] = true;
        $response['message'] = "Thank you! Your message has been sent.";
    } else {
        throw new Exception("Failed to send email");
    }

} catch (Exception $e) {
    $response['message'] = "Sorry, there was an error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

// Return JSON response
echo json_encode($response);
?>