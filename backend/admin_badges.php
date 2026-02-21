<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
session_start();

// Admin-only endpoint
if (!isset($_SESSION['user']) || strtolower($_SESSION['user']['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$adminId = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)) $adminId = $u['id'] ?? $u['user_id'] ?? null;
    elseif (is_int($u)) $adminId = $u;
    elseif (is_string($u) && ctype_digit($u)) $adminId = (int)$u;
}

try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

if (empty($pdo) || !($pdo instanceof PDO)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection not available']);
    exit;
}

$pendingList = [];
$todayList = [];
$pendingUnreadCount = 0;
$todayUnreadCount = 0;
$pendingSeenAt = null;
$scheduleSeenAt = null;
$lastPendingId = null;
$lastScheduleId = null;

function pick_column($columns, $candidates){
    foreach ($candidates as $c) if (isset($columns[$c])) return $c;
    return null;
}

try {
    // Track when admin last marked alerts as read
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_alerts_state (
        admin_user_id BIGINT UNSIGNED PRIMARY KEY,
        pending_seen_at DATETIME DEFAULT NULL,
        schedule_seen_at DATETIME DEFAULT NULL,
        last_pending_id BIGINT UNSIGNED DEFAULT NULL,
        last_schedule_id BIGINT UNSIGNED DEFAULT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    try { $pdo->exec("ALTER TABLE admin_alerts_state ADD COLUMN last_pending_id BIGINT UNSIGNED DEFAULT NULL"); } catch (Exception $_) {}
    try { $pdo->exec("ALTER TABLE admin_alerts_state ADD COLUMN last_schedule_id BIGINT UNSIGNED DEFAULT NULL"); } catch (Exception $_) {}

    if (!empty($adminId)) {
        $stmt = $pdo->prepare('SELECT pending_seen_at, schedule_seen_at, last_pending_id, last_schedule_id FROM admin_alerts_state WHERE admin_user_id = ? LIMIT 1');
        $stmt->execute([intval($adminId)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $pendingSeenAt = $row['pending_seen_at'] ?? null;
        $scheduleSeenAt = $row['schedule_seen_at'] ?? null;
        $lastPendingId = $row['last_pending_id'] ?? null;
        $lastScheduleId = $row['last_schedule_id'] ?? null;
    }
} catch (Exception $e) {
    $pendingSeenAt = null;
    $scheduleSeenAt = null;
    $lastPendingId = null;
    $lastScheduleId = null;
}

try {
    // Discover available columns in applications table
    $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'applications'");
    $colStmt->execute();
    $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    $colSet = array_fill_keys($cols, true);

    $hasStatus = isset($colSet['status']);
    $nameCol = pick_column($colSet, ['full_name', 'name', 'applicant_name']);
    $titleCol = pick_column($colSet, ['job_title', 'position', 'job_position']);
    $dateCol = pick_column($colSet, ['applied_at', 'created_at', 'submitted_at', 'date_applied']);

    if ($hasStatus) {
        $selectParts = ["id"];
        $selectParts[] = $nameCol ? "$nameCol AS full_name" : "'' AS full_name";
        $selectParts[] = $titleCol ? "$titleCol AS job_title" : "'' AS job_title";
        $selectParts[] = $dateCol ? "$dateCol AS applied_at" : "NULL AS applied_at";

        $orderBy = $dateCol ? "$dateCol DESC" : "id DESC";

        $whereBase = "LOWER(TRIM(COALESCE(status, ''))) IN ('','pending','new','applied')";

        // Full list (for display)
        $sqlPending = "SELECT " . implode(', ', $selectParts) . " FROM applications WHERE {$whereBase} ORDER BY {$orderBy} LIMIT 20";
        $stmt = $pdo->query($sqlPending);
        $pendingList = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Unread count (for badge)
        $whereNew = $whereBase;
        $params = [];
        if (!empty($lastPendingId)) {
            $whereNew .= " AND id > ?";
            $params[] = $lastPendingId;
        } elseif ($dateCol && !empty($pendingSeenAt)) {
            $whereNew .= " AND {$dateCol} > ?";
            $params[] = $pendingSeenAt;
        }
        $sqlCount = "SELECT COUNT(*) AS cnt FROM applications WHERE {$whereNew}";
        $stmt = $pdo->prepare($sqlCount);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $pendingUnreadCount = (int)($row['cnt'] ?? 0);
    } else {
        $pendingList = [];
    }
} catch (Exception $e) {
    $pendingList = [];
}

try {
    // Ensure schedules table exists (same shape as schedule_api.php)
    $pdo->exec("CREATE TABLE IF NOT EXISTS schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        applicant_id INT DEFAULT NULL,
        applicant_name VARCHAR(255) DEFAULT '',
        position VARCHAR(255) DEFAULT '',
        scheduled_at DATETIME NOT NULL,
        created_by VARCHAR(100) DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Get today's schedules (full list)
    $today = date('Y-m-d');
    $start = $today . ' 00:00:00';
    $end = $today . ' 23:59:59';
    $sql = 'SELECT id, applicant_name, position, scheduled_at, created_at FROM schedules WHERE scheduled_at BETWEEN ? AND ? ORDER BY scheduled_at ASC LIMIT 20';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$start, $end]);
    $todayList = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // Unread count (for badge)
    $whereNew = 'scheduled_at BETWEEN ? AND ?';
    $params = [$start, $end];
    if (!empty($lastScheduleId)) {
        $whereNew .= ' AND id > ?';
        $params[] = $lastScheduleId;
    } elseif (!empty($scheduleSeenAt)) {
        $whereNew .= ' AND created_at > ?';
        $params[] = $scheduleSeenAt;
    }
    $sqlCount = "SELECT COUNT(*) AS cnt FROM schedules WHERE {$whereNew}";
    $stmt = $pdo->prepare($sqlCount);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    $todayUnreadCount = (int)($row['cnt'] ?? 0);
} catch (Exception $e) {
    $todayList = [];
}

$pendingCount = count($pendingList);
$todayCount = count($todayList);
$total = $pendingUnreadCount + $todayUnreadCount;

echo json_encode([
    'success' => true,
    'pending_applicants' => $pendingUnreadCount,
    'pending_list' => $pendingList,
    'today_interviews' => $todayUnreadCount,
    'today_list' => $todayList,
    'total_alerts' => $total,
    'pending_total' => $pendingCount,
    'today_total' => $todayCount
]);
?>
