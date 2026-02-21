<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
session_start();

try { require_once __DIR__ . '/config.php'; } catch (Exception $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit; }

// resolve user id and role
$userId = null; $userRole = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)){
        $userId = $u['id'] ?? $u['user_id'] ?? null;
        $userRole = $u['role'] ?? null;
    } elseif (is_int($u)) $userId = $u;
    elseif (is_string($u) && ctype_digit($u)) $userId = (int)$u;
}
if (empty($userId) || empty($userRole) || strtolower($userRole) !== 'admin'){
    echo json_encode(['success'=>false,'message'=>'Authentication required','requires_login'=>true]); exit;
}

try{
    // Ensure users table exists (fallbacks handled elsewhere)
    $sql = "SELECT id, first_name, last_name, contact, email, role, created_at, password, profile_picture FROM users ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    echo json_encode(['success'=>true,'data'=>$data]);
    exit;
}catch(Exception $e){
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' get_users error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}

?>