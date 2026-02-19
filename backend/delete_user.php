<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

try { require_once __DIR__ . '/config.php'; } catch (Exception $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit; }

// require admin
$userRole = null; $userId = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)) { $userRole = $u['role'] ?? null; $userId = $u['id'] ?? null; }
}
if (empty($userRole) || strtolower($userRole) !== 'admin'){
    echo json_encode(['success'=>false,'message'=>'Admin required','requires_admin'=>true]); exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;
$target = $data['user_id'] ?? $data['id'] ?? null;
if (empty($target)){
    echo json_encode(['success'=>false,'message'=>'user_id required']); exit;
}
// prevent deleting self
if($userId && intval($userId) === intval($target)){
    echo json_encode(['success'=>false,'message'=>'Cannot delete yourself']); exit;
}

try{
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$target]);
    echo json_encode(['success'=>true]);
}catch(Exception $e){
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' delete_user error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}
?>