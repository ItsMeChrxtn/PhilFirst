<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

$userId = null;
if (!empty($_SESSION['user'])) {
    $u = $_SESSION['user'];
    if (is_array($u)) {
        $userId = $u['id'] ?? $u['user_id'] ?? null;
    } elseif (is_int($u)) {
        $userId = $u;
    } elseif (is_string($u) && ctype_digit($u)) {
        $userId = (int)$u;
    }
}

if (empty($userId)) {
    echo json_encode(['success' => false, 'message' => 'Authentication required', 'requires_login' => true]);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, first_name, last_name, email, contact FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([(int)$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $first = trim((string)($row['first_name'] ?? ''));
    $last = trim((string)($row['last_name'] ?? ''));
    $email = trim((string)($row['email'] ?? ''));
    $contact = trim((string)($row['contact'] ?? ''));

    $fullName = trim($first . ' ' . $last);
    $missing = [];
    if ($fullName === '') $missing[] = 'name';
    if ($email === '') $missing[] = 'email';
    if ($contact === '') $missing[] = 'phone';

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => (int)$row['id'],
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $contact,
            'is_complete' => empty($missing),
            'missing' => $missing
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
