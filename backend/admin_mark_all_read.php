<?php
header('Content-Type: application/json; charset=utf-8');
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
if (empty($adminId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing admin id']);
    exit;
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

try {
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

    // capture current max ids for pending and today's schedules
    $lastPendingId = null;
    $lastScheduleId = null;

    try {
        $sql = "SELECT MAX(id) AS max_id FROM applications WHERE LOWER(TRIM(COALESCE(status, ''))) IN ('','pending','new','applied')";
        $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $lastPendingId = $row['max_id'] ?? null;
    } catch (Exception $_) { $lastPendingId = null; }

    try {
        $today = date('Y-m-d');
        $start = $today . ' 00:00:00';
        $end = $today . ' 23:59:59';
        $stmt = $pdo->prepare('SELECT MAX(id) AS max_id FROM schedules WHERE scheduled_at BETWEEN ? AND ?');
        $stmt->execute([$start, $end]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastScheduleId = $row['max_id'] ?? null;
    } catch (Exception $_) { $lastScheduleId = null; }

    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO admin_alerts_state (admin_user_id, pending_seen_at, schedule_seen_at, last_pending_id, last_schedule_id)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
              pending_seen_at = VALUES(pending_seen_at),
              schedule_seen_at = VALUES(schedule_seen_at),
              last_pending_id = VALUES(last_pending_id),
              last_schedule_id = VALUES(last_schedule_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([intval($adminId), $now, $now, $lastPendingId, $lastScheduleId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' admin_mark_all_read error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error']);
}

?>
