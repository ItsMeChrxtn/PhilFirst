<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

try{
  if(empty($_SESSION['user'])){
    echo json_encode(['success'=>false,'requires_login'=>true,'message'=>'Not signed in']);
    exit;
  }
  $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
  $appId = isset($input['id']) ? (int)$input['id'] : 0;
  if(!$appId) throw new Exception('Missing application id');

  // verify ownership
  $stmt = $pdo->prepare('SELECT user_id, resume_path FROM applications WHERE id = :id');
  $stmt->execute([':id'=>$appId]);
  $row = $stmt->fetch();
  if(!$row) throw new Exception('Application not found');
  $userId = $_SESSION['user']['id'] ?? ($_SESSION['user']['user_id'] ?? null);
  if(!$userId) throw new Exception('Unable to resolve user id');
  if($row['user_id'] != $userId && ($_SESSION['user']['role'] ?? '') !== 'admin'){
    echo json_encode(['success'=>false,'message'=>'Permission denied']); exit;
  }

  // delete resume file if exists
  if(!empty($row['resume_path'])){
    $path = __DIR__ . '/../' . ltrim($row['resume_path'], '/\\');
    if(file_exists($path)) @unlink($path);
  }

  $stmt = $pdo->prepare('DELETE FROM applications WHERE id = :id');
  $stmt->execute([':id'=>$appId]);

  echo json_encode(['success'=>true,'message'=>'Application deleted']);
}catch(Exception $e){
  error_log("delete_application.php error: " . $e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error','error'=>$e->getMessage()]);
}

?>
