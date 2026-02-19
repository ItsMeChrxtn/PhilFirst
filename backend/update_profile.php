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

// Accept multipart/form-data
$first = trim($_POST['firstName'] ?? '');
$last = trim($_POST['lastName'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$email = trim($_POST['email'] ?? '');

// validate
if(!$email){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'Email is required']); exit; }

try{
  // check email uniqueness
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1');
  $stmt->execute([$email, $userId]);
  if($stmt->fetch()){
    echo json_encode(['success'=>false,'message'=>'Email already in use']); exit;
  }

  // ensure profile_picture column exists
  $colStmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users' AND COLUMN_NAME = 'profile_picture'");
  $colStmt->execute([$DB_NAME]);
  $colInfo = $colStmt->fetch();
  if(empty($colInfo) || intval($colInfo['cnt'] ?? 0) === 0){
    $pdo->exec("ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL");
  }

  $profilePath = null;
  if(!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK){
    $f = $_FILES['avatar'];
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $f['tmp_name']);
    finfo_close($finfo);
    if(!array_key_exists($mime, $allowed)){
      echo json_encode(['success'=>false,'message'=>'Unsupported image type']); exit;
    }
    $ext = $allowed[$mime];
    $uploadDir = __DIR__ . '/../uploads/avatars';
    if(!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);
    $filename = 'u' . $userId . '_' . time() . '.' . $ext;
    $dest = $uploadDir . '/' . $filename;
    if(!move_uploaded_file($f['tmp_name'], $dest)){
      echo json_encode(['success'=>false,'message'=>'Failed to save uploaded file']); exit;
    }
    // store web path starting with slash so it's absolute from web root
    $profilePath = '/uploads/avatars/' . $filename;
  }

  // build update query
  $fields = ['first_name'=> $first, 'last_name'=> $last, 'contact'=> $contact, 'email'=> $email];
  if($profilePath) $fields['profile_picture'] = $profilePath;

  $setParts = [];
  $params = [];
  foreach($fields as $k=>$v){ $setParts[] = "`" . $k . "` = :" . $k; $params[":" . $k] = $v; }
  $params[':id'] = $userId;
  $sql = 'UPDATE users SET ' . implode(', ', $setParts) . ' WHERE id = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  // refresh session values
  $stmt = $pdo->prepare('SELECT id, first_name, last_name, email, contact, profile_picture FROM users WHERE id = ? LIMIT 1');
  $stmt->execute([$userId]);
  $u = $stmt->fetch();
  if($u){
    $_SESSION['user']['first_name'] = $u['first_name'];
    $_SESSION['user']['last_name'] = $u['last_name'];
    $_SESSION['user']['email'] = $u['email'];
    $_SESSION['user']['profile_picture'] = $u['profile_picture'];
    $_SESSION['user']['contact'] = $u['contact'] ?? '';
  }

  echo json_encode([
    'success'=>true,
    'message'=>'Profile updated',
    'profile_picture'=>$u['profile_picture'] ?? null,
    'first_name'=>$u['first_name'] ?? null,
    'last_name'=>$u['last_name'] ?? null,
    'contact'=>$u['contact'] ?? null
  ]);
  exit;

}catch(Exception $e){
  http_response_code(500);
  echo json_encode(['success'=>false,'message'=>'Server error','error'=>$e->getMessage()]);
  exit;
}

?>
