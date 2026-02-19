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

$id = $_GET['id'] ?? null;
if(empty($id)){ echo json_encode(['success'=>false,'message'=>'id required']); exit; }

try{
    $sql = "SELECT id, first_name, last_name, contact, email, role, created_at, profile_picture FROM users WHERE id = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    if(!$row) { echo json_encode(['success'=>false,'message'=>'Not found']); exit; }
    echo json_encode(['success'=>true,'data'=>$row]);
}catch(Exception $e){
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' get_user error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}
?>