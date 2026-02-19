<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

try { require_once __DIR__ . '/config.php'; } catch (Exception $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit; }

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

$notifId = $data['notification_id'] ?? $data['notif_id'] ?? null;
$appId = $data['application_id'] ?? $data['applicationId'] ?? null;
if (empty($notifId) && empty($appId)){
    echo json_encode(['success'=>false,'message'=>'notification_id or application_id required']); exit;
}

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
    // Prefer notifications table
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

        if(!empty($notifId)){
            $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$notifId, $userId]);
        } else {
            $sql = "UPDATE notifications SET is_read = 1 WHERE application_id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$appId, $userId]);
        }
        echo json_encode(['success'=>true]);
        exit;
    }catch(Exception $e){
        // fallback to legacy applications.notif_read
        try { $pdo->exec("ALTER TABLE applications ADD COLUMN IF NOT EXISTS notif_read TINYINT(1) NOT NULL DEFAULT 0"); } catch (Exception $_) {}
        $sql = "UPDATE applications SET notif_read = 1 WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$appId, $userId]);
        echo json_encode(['success'=>true]);
        exit;
    }
}catch(Exception $e){
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' mark_notification_read error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}

?>
