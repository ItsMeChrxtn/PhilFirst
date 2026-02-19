<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

try { require_once __DIR__ . '/config.php'; } catch (Exception $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit; }

// resolve user id
$userId = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)) $userId = $u['id'] ?? $u['user_id'] ?? null;
    elseif (is_int($u)) $userId = $u;
    elseif (is_string($u) && ctype_digit($u)) $userId = (int)$u;
}
if (empty($userId)) { echo json_encode(['success'=>false,'message'=>'Authentication required','requires_login'=>true]); exit; }

try{
    // Try notifications table
    try{
        $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            application_id BIGINT UNSIGNED DEFAULT NULL,
            `status` VARCHAR(64) DEFAULT NULL,
            message TEXT DEFAULT NULL,
            resolved_job_title VARCHAR(255) DEFAULT NULL,
            display_status VARCHAR(64) DEFAULT NULL,
            is_read TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $sql = "SELECT id, application_id, `status`, message, is_read, created_at, resolved_job_title, display_status FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }catch(Exception $e){
        // fallback to applications table
        $sql = "SELECT a.id AS id, a.id AS application_id, a.status AS status, a.message AS message, COALESCE(a.notif_read,0) AS is_read, a.created_at AS created_at, COALESCE(jp.title,'') AS resolved_job_title, a.status AS display_status
                FROM applications a
                LEFT JOIN job_postings jp ON a.job_id = jp.id
                WHERE a.user_id = ?
                ORDER BY a.created_at DESC LIMIT 50";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }
}catch(Exception $e){
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' get_notifications error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}

?>