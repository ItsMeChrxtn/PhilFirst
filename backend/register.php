<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

// use shared DB config
try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    $log = __DIR__ . '/error.log';
    $msg = date('c') . ' DB connect error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    file_put_contents($log, $msg, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed', 'error' => $e->getMessage()]);
    exit;
}

$log = __DIR__ . '/error.log';

// Using existing `users` table in the database (no auto-create)

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!is_array($data)) $data = $_POST;

$first = trim($data['firstName'] ?? '');
$last = trim($data['lastName'] ?? '');
$contact = trim($data['contact'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

// default role always applicant regardless of input
$role = 'applicant';

// check existing
try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    // check if users table has `password` column
    $colStmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users' AND COLUMN_NAME = 'password'");
    $colStmt->execute([$DB_NAME]);
    $colInfo = $colStmt->fetch();
    if (empty($colInfo) || intval($colInfo['cnt'] ?? 0) === 0) {
        // helpful error: users table missing password column
        $errMsg = 'users table is missing the `password` column. Run: ALTER TABLE users ADD COLUMN password VARCHAR(255) NULL;';
        file_put_contents($log, date('c') . ' register schema error: ' . $errMsg . PHP_EOL, FILE_APPEND | LOCK_EX);
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error during registration', 'error' => $errMsg]);
        exit;
    }
    $ins = $pdo->prepare('INSERT INTO users (first_name,last_name,contact,email,role,password,created_at) VALUES (:f,:l,:c,:e,:r,:p,NOW())');
    $ins->execute([
        ':f' => $first,
        ':l' => $last,
        ':c' => $contact,
        ':e' => $email,
        ':r' => $role,
        ':p' => $hash,
    ]);

    $id = $pdo->lastInsertId();
} catch (Exception $e) {
    // log to backend/error.log and return JSON error (useful for debugging)
    $log = __DIR__ . '/error.log';
    $msg = date('c') . ' register error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    file_put_contents($log, $msg, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error during registration', 'error' => $e->getMessage()]);
    exit;
}
// set session
$_SESSION['user'] = ['id' => $id, 'email' => $email, 'role' => $role, 'first_name' => $first, 'last_name' => $last];

echo json_encode(['success' => true, 'message' => 'Registered', 'role' => $role]);

?>
