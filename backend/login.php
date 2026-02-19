<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

// use shared DB config
try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed', 'error' => $e->getMessage()]);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!is_array($data)) $data = $_POST;

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

// Ensure users table exists (in case register created it previously)
$stmt = $pdo->prepare('SELECT id, first_name, last_name, email, role, password, contact, profile_picture FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}

// verify password
if (empty($user['password']) || !password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}

// set session (include contact and avatar if present)
$_SESSION['user'] = [
    'id' => $user['id'],
    'email' => $user['email'],
    'role' => $user['role'],
    'first_name' => $user['first_name'],
    'last_name' => $user['last_name'],
    'contact' => $user['contact'] ?? '',
    'profile_picture' => $user['profile_picture'] ?? ''
];

echo json_encode(['success' => true, 'message' => 'Logged in', 'role' => $user['role']]);

?>
