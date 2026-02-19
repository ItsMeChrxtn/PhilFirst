<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (empty($_SESSION['user']['id'])){
  http_response_code(401);
  echo json_encode(['success'=>false,'message'=>'Not signed in','requires_login'=>true]);
  exit;
}

try{ require_once __DIR__ . '/config.php'; }catch(Exception $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB error','error'=>$e->getMessage()]); exit; }

$userId = intval($_SESSION['user']['id']);
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if(!is_array($data)) $data = $_POST;

$old = trim($data['currentPassword'] ?? '');
$new = trim($data['newPassword'] ?? '');
if(!$old || !$new){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'Current and new password required']); exit; }

try{
  $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
  $stmt->execute([$userId]);
  $row = $stmt->fetch();
  if(!$row || empty($row['password']) || !password_verify($old, $row['password'])){
    echo json_encode(['success'=>false,'message'=>'Current password is incorrect']); exit;
  }
  $hash = password_hash($new, PASSWORD_DEFAULT);
  $up = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
  $up->execute([$hash, $userId]);
  echo json_encode(['success'=>true,'message'=>'Password updated']); exit;
}catch(Exception $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Server error','error'=>$e->getMessage()]); exit; }

?>
