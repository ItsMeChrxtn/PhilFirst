<?php
header('Content-Type: application/json; charset=utf-8');

// Allow CORS for simple deployments (adjust on Hostinger as needed)
// header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!is_array($data)) {
    // Fallback to form-data
    $data = $_POST;
}

$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

if (empty($name) || empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Name and email are required']);
    exit;
}

// Append to contacts file
$record = [
    'time' => date('c'),
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message
];
$dir = __DIR__;
$file = $dir . '/contacts.txt';
$line = json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL;
file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

// Optionally send email (server must be configured)
$to = 'recruitment@phil-first.example';
$subject = 'New contact from website: ' . $name;
$body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message\n";
$headers = "From: noreply@{$_SERVER['HTTP_HOST']}\r\nReply-To: $email";
@mail($to, $subject, $body, $headers);

echo json_encode(['success' => true]);
