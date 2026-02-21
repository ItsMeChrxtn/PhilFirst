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

function resolve_user_id_from_session() {
    if (empty($_SESSION['user'])) return null;
    $u = $_SESSION['user'];
    if (is_array($u)) return isset($u['id']) ? (int)$u['id'] : (isset($u['user_id']) ? (int)$u['user_id'] : null);
    if (is_int($u)) return $u;
    if (is_string($u) && ctype_digit($u)) return (int)$u;
    return null;
}

function normalize_status($status) {
    $status = strtolower((string)$status);
    return preg_replace('/[^a-z0-9]/', '', $status);
}

$userId = resolve_user_id_from_session();
if (empty($userId)) {
    echo json_encode(['success' => false, 'message' => 'Authentication required', 'requires_login' => true]);
    exit;
}

$jobIdRaw = $_REQUEST['job_id'] ?? null;
$jobId = is_numeric($jobIdRaw) ? (int)$jobIdRaw : null;

if (empty($jobId)) {
    echo json_encode(['success' => false, 'message' => 'job_id is required']);
    exit;
}

try {
    $sql = 'SELECT status, created_at FROM applications WHERE user_id = :user_id AND job_id = :job_id ORDER BY created_at DESC';
    $params = [':user_id' => $userId, ':job_id' => $jobId];
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare: ' . json_encode($pdo->errorInfo()));
    }
    if (!$stmt->execute($params)) {
        throw new Exception('Execute failed: ' . json_encode($stmt->errorInfo()));
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows || count($rows) === 0) {
        echo json_encode(['success' => true, 'can_apply' => true, 'message' => 'No previous application found']);
        exit;
    }

    $blockedStatuses = ['pending', 'forinterview', 'interview', 'accepted'];
    foreach ($rows as $row) {
        $statusRaw = $row['status'] ?? '';
        $statusNorm = normalize_status($statusRaw);
        if (in_array($statusNorm, $blockedStatuses, true)) {
            echo json_encode([
                'success' => true,
                'can_apply' => false,
                'already_applied' => true,
                'status' => $statusRaw,
                'message' => 'Already applied to this job. Current status: ' . $statusRaw
            ]);
            exit;
        }
    }

    echo json_encode([
        'success' => true,
        'can_apply' => true,
        'already_applied' => true,
        'status' => $rows[0]['status'] ?? 'Rejected',
        'message' => 'Previous application is rejected. You can apply again.'
    ]);
} catch (Exception $e) {
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' check_application_status error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL, FILE_APPEND | LOCK_EX);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
