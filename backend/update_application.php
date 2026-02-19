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

  // Only allow updating certain fields
  $allowed = ['status','message'];
  $fields = [];
  $params = [];
  foreach($allowed as $f){
    if(isset($input[$f])){
      $fields[] = "{$f} = :{$f}";
      $params[":{$f}"] = $input[$f];
    }
  }
  if(empty($fields)) throw new Exception('No updatable fields provided');

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

  $sql = 'UPDATE applications SET ' . implode(', ', $fields) . ' WHERE id = :id';
  $params[':id'] = $appId;
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  // If status was changed, create a notification record for the applicant (do not overwrite old notifications)
  if (isset($params[':status'])){
    try{
      @file_put_contents(__DIR__.'/error.log', date('c')." update_application: creating notification for app {$appId}, status={$params[':status']}\n", FILE_APPEND | LOCK_EX);
      // create notifications table if missing (include resolved job title and display status)
      $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        application_id BIGINT UNSIGNED DEFAULT NULL,
        `status` VARCHAR(64) DEFAULT NULL,
        message TEXT DEFAULT NULL,
        resolved_job_title VARCHAR(255) DEFAULT NULL,
        display_status VARCHAR(64) DEFAULT NULL,
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX (user_id), INDEX (application_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
      // attempt to add columns if table exists but missing them
      try{ $pdo->exec("ALTER TABLE notifications ADD COLUMN IF NOT EXISTS resolved_job_title VARCHAR(255) DEFAULT NULL"); }catch(Exception $_){}
      try{ $pdo->exec("ALTER TABLE notifications ADD COLUMN IF NOT EXISTS display_status VARCHAR(64) DEFAULT NULL"); }catch(Exception $_){}

      // get application owner and job info (verify joins)
      $ownerStmt = $pdo->prepare('SELECT a.user_id, a.job_id, a.status AS app_status, jp.title AS job_title FROM applications a LEFT JOIN job_postings jp ON a.job_id = jp.id WHERE a.id = ?');
      $ownerStmt->execute([$appId]);
      $ownerRow = $ownerStmt->fetch(PDO::FETCH_ASSOC);
      if($ownerRow && !empty($ownerRow['user_id'])){
        // normalize display status
        $raw = strtolower((string)($ownerRow['app_status'] ?? $params[':status'] ?? ''));
        $display = $ownerRow['app_status'] ?? $params[':status'] ?? '';
        if(in_array($raw, ['approved','for interview'])) $display = 'For Interview';
        else if($raw === 'accepted') $display = 'Accepted';
        else if(in_array($raw, ['declined','rejected'])) $display = 'Rejected';
        else if($raw === 'pending') $display = 'Pending';

        $jobTitle = $ownerRow['job_title'] ?? null;

        $ins = $pdo->prepare('INSERT INTO notifications (user_id, application_id, `status`, message, resolved_job_title, display_status, is_read) VALUES (?, ?, ?, ?, ?, ?, 0)');
        $ins->execute([$ownerRow['user_id'], $appId, $params[':status'], $input['message'] ?? null, $jobTitle, $display]);
        @file_put_contents(__DIR__.'/error.log', date('c')." update_application: notification inserted for user {$ownerRow['user_id']} app {$appId} job_title=".($jobTitle?:'')." display_status={$display}\n", FILE_APPEND | LOCK_EX);
      }
    }catch(Exception $e){
      // log but don't fail the update
      @file_put_contents(__DIR__.'/error.log', date('c')." notification insert failed: " . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
  }

  echo json_encode(['success'=>true,'message'=>'Application updated']);
}catch(Exception $e){
  error_log("update_application.php error: " . $e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error','error'=>$e->getMessage()]);
}

?>
