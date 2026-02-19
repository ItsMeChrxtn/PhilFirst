<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

try { require_once __DIR__ . '/config.php'; } catch (Exception $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit; }

// require admin
$userRole = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)) $userRole = $u['role'] ?? null;
}
if (empty($userRole) || strtolower($userRole) !== 'admin'){
    echo json_encode(['success'=>false,'message'=>'Admin required','requires_admin'=>true]); exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;
$target = $data['user_id'] ?? $data['id'] ?? null;
$newRole = $data['role'] ?? null;
if (empty($target) || empty($newRole)){
    echo json_encode(['success'=>false,'message'=>'user_id and role required']); exit;
}

$allowed = ['admin','hr','user','applicant'];
if(!in_array(strtolower($newRole), $allowed)){
    echo json_encode(['success'=>false,'message'=>'Invalid role']); exit;
}

try{
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([strtolower($newRole), $target]);
    echo json_encode(['success'=>true]);
}catch(Exception $e){
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' update_user_role error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}
?>