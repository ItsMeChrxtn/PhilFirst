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

// Ensure $pdo is available and a PDO instance
if (empty($pdo) || !($pdo instanceof PDO)){
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection not available']);
    exit;
}

/**
 * Hint for static analyzers: `$pdo` is a PDO instance when reached below.
 * @var \PDO $pdo
 */

// optional debug toggle (only returns extra info when explicitly requested)
$includeDebug = isset($_GET['debug_apps']) && $_GET['debug_apps'] === '1';

// resolve user id from session (compatible with different session shapes)
$userId = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)){
        $userId = $u['id'] ?? $u['user_id'] ?? null;
        $userRole = $u['role'] ?? null;
    } elseif (is_int($u)) {
        $userId = $u;
        $userRole = null;
    } elseif (is_string($u) && ctype_digit($u)){
        $userId = (int)$u;
        $userRole = null;
    }
}
if (empty($userId)){
    echo json_encode(['success' => false, 'message' => 'Authentication required', 'requires_login' => true]);
    exit;
}

try {
    // Resolve job title/location from job_postings when job_id is present.
    // Admin users can retrieve all applications; regular users only their own.
    $isAdmin = (!empty($userRole) && strtolower($userRole) === 'admin');
    $sqlBase = "SELECT a.*, 
        COALESCE(jp.title, '') AS resolved_job_title,
        COALESCE(jp.job_description, '') AS resolved_job_description,
        COALESCE(jp.location, '') AS resolved_location
        FROM applications a
        LEFT JOIN job_postings jp ON a.job_id = jp.id";
    if ($isAdmin) {
        $sql = $sqlBase . " ORDER BY a.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = $sqlBase . " WHERE a.user_id = ? ORDER BY a.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
    }
    // log SQL for debugging
    @file_put_contents(__DIR__ . '/error.log', date('c') . " get_applications SQL: {$sql}\n", FILE_APPEND | LOCK_EX);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = ['success' => true, 'data' => $data];
    if($includeDebug){ $out['debug'] = ['user_id' => $userId, 'sql' => $sql, 'rows_found' => count($data)]; }
    echo json_encode($out);
} catch (Exception $e) {
    // log error for debugging
    $err = __DIR__ . '/error.log';
    @file_put_contents($err, date('c') . ' get_applications error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    $out = ['success' => false, 'message' => 'Database error', 'error' => $e->getMessage()];
    if($includeDebug) $out['debug'] = ['user_id' => $userId ?? null, 'sql' => $sql ?? null];
    echo json_encode($out);
}

?>
