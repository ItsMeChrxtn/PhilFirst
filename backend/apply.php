<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

// debug logger (writes to logs/apply_debug.log if writable on server)
function apply_log($obj){
    $dir = __DIR__ . '/../logs';
    if(!is_dir($dir)) @mkdir($dir, 0755, true);
    $file = $dir . '/apply_debug.log';
    $line = '['.date('Y-m-d H:i:s').'] '.(is_string($obj)?$obj:json_encode($obj, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))."\n";
    @file_put_contents($file, $line, FILE_APPEND);
}

// wrap main flow to catch fatal/unexpected errors and log them for debugging
try {
    apply_log(['start_request'=>true,'method'=>$_SERVER['REQUEST_METHOD'] ?? '']);

    // rest of script continues below
} catch (Throwable $e) {
    // log to backend error log and the debug log
    $errFile = __DIR__ . '/error.log';
    $msg = date('c') . ' apply fatal: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    @file_put_contents($errFile, $msg, FILE_APPEND | LOCK_EX);
    apply_log(['fatal_error'=>$e->getMessage()]);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Server error','error'=>$e->getMessage()]);
    exit;
}

// require DB
try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

// resolve user id from session (handle several session shapes)
function resolve_session_user_id(){
    if (empty($_SESSION)) return null;
    if (isset($_SESSION['user'])){
        $u = $_SESSION['user'];
        if (is_int($u)) return $u;
        if (is_string($u) && ctype_digit($u)) return (int)$u;
        if (is_array($u)){
            foreach (['id','user_id','userId','userid'] as $k) if (!empty($u[$k])) return (int)$u[$k];
        }
    }
    // fallback top-level keys
    foreach (['user_id','userId','userid'] as $k) if (!empty($_SESSION[$k])) return (int)$_SESSION[$k];
    return null;
}

$userId = resolve_session_user_id();
apply_log(['resolved_session_user_id'=>$userId]);
if (empty($userId)){
    echo json_encode(['success' => false, 'message' => 'Authentication required', 'requires_login' => true]);
    exit;
}

// Accept POST (multipart/form-data) with: job_id, full_name, email, phone, message, resume (file)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

// capture incoming data for debugging
$job_id_raw = $_POST['job_id'] ?? null;
$job_id = is_numeric($job_id_raw) ? (int)$job_id_raw : null;
$name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
$job_title = trim($_POST['job_title'] ?? '');
apply_log(['post'=>array_intersect_key($_POST,array_flip(['job_id','job_title','full_name','email','phone','message'])), 'files'=>array_keys($_FILES), 'session_user'=>($_SESSION['user'] ?? null)]);
if (!$job_id && $job_title === '') {
    echo json_encode(['success' => false, 'message' => 'Missing job id or title']);
    exit;
}

// verify job exists (table may be job_postings or jobs) -- handle columns named 'id' or 'job_id'
// Lookup job in job_postings (primary source)
$foundJob = null;
try {
    if ($job_id) {
        $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE id = ? LIMIT 1");
        $stmt->execute([$job_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE title = ? OR job_title = ? LIMIT 1");
        $stmt->execute([$job_title, $job_title]);
    }
    $r = $stmt->fetch();
    apply_log(['table'=>'job_postings','found'=>!!$r,'row'=>$r?$r:null]);
    if ($r) $foundJob = $r;
} catch (Exception $e) {
    apply_log(['table_error'=>'job_postings','error'=>$e->getMessage()]);
}

// fallback: try a generic 'jobs' table only if job_postings did not match
if (!$foundJob) {
    foreach (['jobs'] as $tbl) {
        try {
            if ($job_id) {
                $stmt = $pdo->prepare("SELECT * FROM {$tbl} WHERE id = ? OR job_id = ? LIMIT 1");
                $stmt->execute([$job_id, $job_id]);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM {$tbl} WHERE title = ? OR job_title = ? LIMIT 1");
                $stmt->execute([$job_title, $job_title]);
            }
            $r = $stmt->fetch();
            apply_log(['table'=>$tbl,'found'=>!!$r,'row'=> $r ? $r : null]);
            if ($r) { $foundJob = $r; break; }
        } catch (Exception $e) {
            apply_log(['table_error'=>$tbl,'error'=>$e->getMessage()]);
        }
    }
}
if (!$foundJob) {
    // as a last resort try LIKE search on titles if title provided
    if ($job_title !== '') {
        foreach (['job_postings','jobs'] as $tbl) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM {$tbl} WHERE title LIKE ? OR job_title LIKE ? LIMIT 1");
                $stmt->execute(["%{$job_title}%", "%{$job_title}%"]);
                $r = $stmt->fetch();
                apply_log(['like_search'=>$tbl,'like_found'=>!!$r,'row'=>$r? $r : null]);
                if ($r) { $foundJob = $r; break; }
            } catch (Exception $e) { apply_log(['like_error'=>$tbl,'error'=>$e->getMessage()]); }
        }
    }
}
if (!$foundJob) {
    apply_log(['result'=>'not_found','job_id'=>$job_id,'job_title'=>$job_title]);
    // If no exact job found but a title was provided, allow saving the application
    // while recording the provided job title. This avoids blocking applicants
    // when the jobs table uses different naming or the id is not present.
    if ($job_title !== '') {
        $store_job_id = null;
        $store_job_title = $job_title;
        apply_log(['fallback_store'=>'using_job_title_only','job_title'=>$job_title]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Job not found']);
        exit;
    }
} else {
    $store_job_title = $foundJob['title'] ?? ($foundJob['job_title'] ?? '');
}

// handle upload
$resume_path = null;
if (!empty($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $uploads = __DIR__ . '/../uploads/resumes';
    if (!is_dir($uploads)) mkdir($uploads, 0755, true);
    $orig = basename($_FILES['resume']['name']);
    $ext = pathinfo($orig, PATHINFO_EXTENSION);
    $newName = 'resume_' . time() . '_' . bin2hex(random_bytes(6)) . ($ext? '.' . $ext : '');
    $dest = $uploads . '/' . $newName;
    if (move_uploaded_file($_FILES['resume']['tmp_name'], $dest)) {
        $resume_path = 'uploads/resumes/' . $newName;
    }
}

// Assume `applications` table already exists in user's DB; do not attempt to create it here.
apply_log('skipping_create_table_assume_existing_schema');

try {
    // determine job id/title to store
    if (!isset($store_job_id)) {
        $store_job_id = $foundJob['id'] ?? ($foundJob['job_id'] ?? ($job_id ?? null));
    }
    if (!isset($store_job_title)) {
        $store_job_title = $foundJob['title'] ?? ($foundJob['job_title'] ?? ($job_title ?? null));
    }

    // derive job description and location from found job when available
    $job_description = '';
    $job_location = '';
    if (!empty($foundJob) && is_array($foundJob)){
        $job_description = $foundJob['job_description'] ?? $foundJob['description'] ?? $foundJob['jobDescription'] ?? '';
        $job_location = $foundJob['location'] ?? $foundJob['job_location'] ?? '';
    }

    // discover which columns exist in `applications` and build insert dynamically
    $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'applications'");
    $colStmt->execute([$DB_NAME]);
    $appCols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
    apply_log(['applications_table_columns'=>$appCols]);

    $insertCols = [];
    $placeholders = [];
    $params = [];

    $maybeAdd = function($col, $placeholder, $value) use (&$insertCols,&$placeholders,&$params,$appCols){
        if (in_array($col, $appCols)){
            $insertCols[] = $col;
            $placeholders[] = $placeholder;
            $params[trim($placeholder, ':')] = $value;
        }
    };

    $maybeAdd('job_id', ':job_id', $store_job_id);
    $maybeAdd('job_title', ':job_title', $store_job_title);
    $maybeAdd('job_description', ':job_description', $job_description);
    $maybeAdd('location', ':location', $job_location);
    // set application status to pending if column exists
    $maybeAdd('status', ':status', 'Pending');
    $maybeAdd('user_id', ':user_id', $userId);
    $maybeAdd('full_name', ':full_name', $name);
    $maybeAdd('email', ':email', $email);
    $maybeAdd('phone', ':phone', $phone);
    $maybeAdd('message', ':message', $message);
    $maybeAdd('resume_path', ':resume_path', $resume_path);

    if (empty($insertCols)){
        throw new Exception('No writable columns found in applications table');
    }

    $sql = 'INSERT INTO applications (' . implode(',', $insertCols) . ') VALUES (' . implode(',', array_map(function($p){ return ':' . $p; }, array_map(function($ph){ return ltrim($ph, ':'); }, $placeholders))) . ')';
    // prepare params array with colon-prefixed keys for execute
    $execParams = [];
    foreach ($params as $k=>$v) $execParams[':'.$k] = $v;
    apply_log(['insert_sql'=>$sql,'params'=>array_keys($execParams)]);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($execParams);
    echo json_encode(['success' => true, 'message' => 'Application submitted']);
} catch (Exception $e) {
    // return error message for debugging (do not expose in production)
    http_response_code(500);
    apply_log(['db_error'=>$e->getMessage()]);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

?>
