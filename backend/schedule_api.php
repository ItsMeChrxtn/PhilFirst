<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

if (empty($pdo) || !($pdo instanceof PDO)){
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection not available']);
    exit;
}

// simple admin check (requires session user and role 'admin')
$userRole = null;
if(!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if(is_array($u)) $userRole = $u['role'] ?? null;
}

// Ensure schedules table exists
try{
    $pdo->exec("CREATE TABLE IF NOT EXISTS schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        applicant_id INT DEFAULT NULL,
        applicant_name VARCHAR(255) DEFAULT '',
        position VARCHAR(255) DEFAULT '',
        scheduled_at DATETIME NOT NULL,
        created_by VARCHAR(100) DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}catch(Exception $e){ /* ignore create errors */ }

$method = $_SERVER['REQUEST_METHOD'];
// Read php://input only ONCE since it can only be read once
$rawInput = file_get_contents('php://input');
$rawBody = ($rawInput ? json_decode($rawInput, true) : null) ?: $_POST;
// support method override via JSON body param `_method` or header `X-HTTP-Method-Override`
if(!empty($rawBody['_method'])){
    $method = strtoupper($rawBody['_method']);
}elseif(!empty($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])){
    $method = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
}
if($method === 'GET'){
    // optional date param (YYYY-MM-DD) to filter
    $date = isset($_GET['date']) ? trim($_GET['date']) : '';
    try{
        if($date){
            $start = $date . ' 00:00:00';
            $end = $date . ' 23:59:59';
            $stmt = $pdo->prepare('SELECT * FROM schedules WHERE scheduled_at BETWEEN ? AND ? ORDER BY scheduled_at ASC');
            $stmt->execute([$start,$end]);
        } else {
            $stmt = $pdo->query('SELECT * FROM schedules ORDER BY scheduled_at ASC');
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);
    }catch(Exception $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB error','error'=>$e->getMessage()]); }
    exit;
}

if($method === 'POST'){
    // only admin allowed to create schedules
    if(empty($userRole) || strtolower($userRole) !== 'admin'){
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'Unauthorized']);
        exit;
    }
    $raw = $rawBody; // use pre-read input
    $applicant_id = isset($raw['applicant_id']) ? $raw['applicant_id'] : null;
    $applicant_name = isset($raw['applicant_name']) ? trim($raw['applicant_name']) : '';
    $position = isset($raw['position']) ? trim($raw['position']) : '';
    $scheduled_at = isset($raw['scheduled_at']) ? trim($raw['scheduled_at']) : '';
    if(!$scheduled_at){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'scheduled_at required']); exit; }
    try{
        $created_by = (is_array($_SESSION['user']) && !empty($_SESSION['user']['name'])) ? $_SESSION['user']['name'] : (is_string($_SESSION['user'])?$_SESSION['user']:'');
        $stmt = $pdo->prepare('INSERT INTO schedules (applicant_id, applicant_name, position, scheduled_at, created_by) VALUES (?,?,?,?,?)');
        $stmt->execute([$applicant_id, $applicant_name, $position, $scheduled_at, $created_by]);
        $id = $pdo->lastInsertId();
        echo json_encode(['success'=>true,'id'=>$id]);
    }catch(Exception $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB error','error'=>$e->getMessage()]); }
    exit;
}

if($method === 'DELETE'){
    // only admin allowed to delete schedules
    if(empty($userRole) || strtolower($userRole) !== 'admin'){
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'Unauthorized']);
        exit;
    }
    // id can be passed as query param: ?id=123
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    // or parse raw input for clients that send JSON
    if(!$id && is_array($rawBody) && !empty($rawBody['id'])){
        $id = intval($rawBody['id']);
    }
    if(!$id){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'id required']); exit; }
    try{
        $stmt = $pdo->prepare('DELETE FROM schedules WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success'=>true,'deleted_id'=>$id]);
    }catch(Exception $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB error','error'=>$e->getMessage()]); }
    exit;
}

http_response_code(405);
echo json_encode(['success'=>false,'message'=>'Method not allowed']);
exit;

?>
