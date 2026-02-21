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

function normalize_resume_text($text){
    $text = (string)$text;
    $text = strtolower($text);
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

function extract_text_from_docx($filePath){
    if (!class_exists('ZipArchive')) return '';
    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) return '';
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    if ($xml === false) return '';
    $text = strip_tags($xml);
    return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function docx_has_embedded_image($filePath){
    if (!class_exists('ZipArchive')) return false;
    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) return false;
    $hasImage = false;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if (is_string($name) && strpos($name, 'word/media/') === 0) {
            $hasImage = true;
            break;
        }
    }
    $zip->close();
    return $hasImage;
}

function extract_text_from_pdf($filePath){
    $content = @file_get_contents($filePath);
    if ($content === false) return '';

    $parts = [];
    if (preg_match_all('/\(([^()]*)\)/s', $content, $matches)) {
        foreach ($matches[1] as $m) {
            $parts[] = preg_replace('/\\\\([nrtbf()\\\\])/', ' ', $m);
        }
    }

    if (preg_match_all('/stream\r?\n(.*?)\r?\nendstream/s', $content, $streams)) {
        foreach ($streams[1] as $stream) {
            $decoded = @gzuncompress($stream);
            if ($decoded !== false && preg_match_all('/\(([^()]*)\)/s', $decoded, $decodedMatches)) {
                foreach ($decodedMatches[1] as $m) {
                    $parts[] = preg_replace('/\\\\([nrtbf()\\\\])/', ' ', $m);
                }
            }
        }
    }

    $text = implode(' ', $parts);
    $text = preg_replace('/[^\p{L}\p{N}\s\-.,:@]/u', ' ', $text);
    return trim($text);
}

function pdf_has_embedded_image($filePath){
    $content = @file_get_contents($filePath);
    if ($content === false) return false;
    return (bool)preg_match('/\/Subtype\s*\/Image/i', $content);
}

function extract_text_from_doc($filePath){
    $content = @file_get_contents($filePath);
    if ($content === false) return '';
    $text = preg_replace('/[^\x20-\x7E\r\n\t]/', ' ', $content);
    return trim($text);
}

function extract_resume_text($filePath, $ext){
    if ($ext === 'docx') return extract_text_from_docx($filePath);
    if ($ext === 'pdf') return extract_text_from_pdf($filePath);
    if ($ext === 'doc') return extract_text_from_doc($filePath);
    return '';
}

function has_name_indicator($rawText, $normalizedText){
    if (preg_match('/\b(full\s*name|name)\b/i', $normalizedText)) return true;
    if (preg_match('/\b([A-Z][a-z]{1,20}\s+[A-Z][a-z]{1,20})(\s+[A-Z][a-z]{1,20})?\b/', (string)$rawText)) return true;
    return false;
}

function has_experience_indicator($normalizedText){
    return (bool)preg_match('/\b(experience|work\s*experience|employment\s*history|work\s*history|job\s*experience)\b/i', $normalizedText);
}

function has_resume_keyword_indicator($normalizedText){
    return (bool)preg_match('/\b(resume|curriculum\s*vitae|cv|biodata|bio\s*data|education|skills|contact|objective|summary|references?)\b/i', $normalizedText);
}

function has_profile_photo_indicator($filePath, $ext){
    if ($ext === 'docx') return docx_has_embedded_image($filePath);
    if ($ext === 'pdf') return pdf_has_embedded_image($filePath);
    return false;
}

function validate_resume_indicators($filePath, $ext, $text){
    $normalized = normalize_resume_text($text);
    $nameFound = has_name_indicator($text, $normalized);
    $experienceFound = has_experience_indicator($normalized);
    $keywordFound = has_resume_keyword_indicator($normalized);
    $photoFound = has_profile_photo_indicator($filePath, $ext);
    $hasAnyIndicator = $nameFound || $experienceFound || $keywordFound || $photoFound;

    return [
        'valid' => $hasAnyIndicator,
        'nameFound' => $nameFound,
        'experienceFound' => $experienceFound,
        'keywordFound' => $keywordFound,
        'photoFound' => $photoFound,
        'textLength' => strlen((string)$text)
    ];
}

function normalize_application_status($status){
    return preg_replace('/[^a-z0-9]/', '', strtolower((string)$status));
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
$message = trim($_POST['message'] ?? '');
$job_title = trim($_POST['job_title'] ?? '');
apply_log(['post'=>array_intersect_key($_POST,array_flip(['job_id','job_title','message'])), 'files'=>array_keys($_FILES), 'session_user'=>($_SESSION['user'] ?? null)]);
if (!$job_id && $job_title === '') {
    echo json_encode(['success' => false, 'message' => 'Missing job id or title']);
    exit;
}

// get applicant details from logged-in account (ignore manual form overrides)
try {
    $uStmt = $pdo->prepare('SELECT first_name, last_name, email, contact FROM users WHERE id = ? LIMIT 1');
    $uStmt->execute([$userId]);
    $uRow = $uStmt->fetch(PDO::FETCH_ASSOC) ?: [];
    $firstName = trim((string)($uRow['first_name'] ?? ''));
    $lastName = trim((string)($uRow['last_name'] ?? ''));
    $name = trim($firstName . ' ' . $lastName);
    $email = trim((string)($uRow['email'] ?? ''));
    $phone = trim((string)($uRow['contact'] ?? ''));
    $missingProfile = [];
    if ($name === '') $missingProfile[] = 'name';
    if ($email === '') $missingProfile[] = 'email';
    if ($phone === '') $missingProfile[] = 'phone';
    if (!empty($missingProfile)) {
        echo json_encode([
            'success' => false,
            'requires_profile_completion' => true,
            'missing' => $missingProfile,
            'message' => 'Please complete your profile details in Settings before applying.'
        ]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Unable to load logged-in profile details']);
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

if (!isset($store_job_id)) {
    $store_job_id = $foundJob['id'] ?? ($foundJob['job_id'] ?? ($job_id ?? null));
}
if (!isset($store_job_title)) {
    $store_job_title = $foundJob['title'] ?? ($foundJob['job_title'] ?? ($job_title ?? null));
}

// duplicate/spam guard: block if existing status is pending/for interview/accepted
try {
    if (!empty($store_job_id)) {
        $dupSql = 'SELECT status FROM applications WHERE user_id = :user_id AND job_id = :job_id ORDER BY created_at DESC';
        $dupStmt = $pdo->prepare($dupSql);
        $dupStmt->execute([':user_id' => $userId, ':job_id' => (int)$store_job_id]);
        $existingRows = $dupStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $existingRows = [];
    }

    if (!empty($existingRows)) {

        $blockedStatuses = ['pending', 'forinterview', 'interview', 'accepted'];
        foreach ($existingRows as $existing) {
            $statusRaw = (string)($existing['status'] ?? '');
            $normalizedStatus = normalize_application_status($statusRaw);
            if (in_array($normalizedStatus, $blockedStatuses, true)) {
                echo json_encode([
                    'success' => false,
                    'already_applied' => true,
                    'can_reapply' => false,
                    'status' => $statusRaw,
                    'message' => 'Already applied to this job. Current status: ' . $statusRaw
                ]);
                exit;
            }
        }
    }
} catch (Exception $e) {
    apply_log(['duplicate_check_error' => $e->getMessage()]);
    apply_log(['error_details' => $e->getMessage()]);
}

// handle upload
$resume_path = null;
if (empty($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Resume/Biodata file is required']);
    exit;
}

if (!empty($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $uploads = __DIR__ . '/../uploads/resumes';
    if (!is_dir($uploads)) mkdir($uploads, 0755, true);
    $orig = basename($_FILES['resume']['name']);
    $origLower = strtolower($orig);
    if (!preg_match('/(resume|bio\s*data|biodata)/i', $origLower)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file name. Filename must contain resume or biodata']);
        exit;
    }

    $ext = pathinfo($orig, PATHINFO_EXTENSION);
    $extLower = strtolower($ext);
    $allowedExt = ['pdf', 'doc', 'docx'];
    if (!in_array($extLower, $allowedExt, true)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: PDF, DOC, DOCX']);
        exit;
    }

    $allowedMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMime = $finfo ? finfo_file($finfo, $_FILES['resume']['tmp_name']) : null;
        if ($finfo) finfo_close($finfo);
        if ($detectedMime && !in_array($detectedMime, $allowedMimes, true)) {
            echo json_encode(['success' => false, 'message' => 'Invalid document content. Please upload a valid PDF/DOC/DOCX resume']);
            exit;
        }
    }

    $extractedText = extract_resume_text($_FILES['resume']['tmp_name'], $extLower);
    $resumeCheck = validate_resume_indicators($_FILES['resume']['tmp_name'], $extLower, $extractedText);
    apply_log(['resume_text_check'=>array_merge(['ext'=>$extLower], $resumeCheck)]);
    if (empty($resumeCheck['valid'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid file: walang nakita na name, experience, resume/biodata details, o profile picture.']);
        exit;
    }

    $newName = 'resume_' . time() . '_' . bin2hex(random_bytes(6)) . ($ext? '.' . $ext : '');
    $dest = $uploads . '/' . $newName;
    if (move_uploaded_file($_FILES['resume']['tmp_name'], $dest)) {
        $resume_path = 'uploads/resumes/' . $newName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload resume file']);
        exit;
    }
}

// Assume `applications` table already exists in user's DB; do not attempt to create it here.
apply_log('skipping_create_table_assume_existing_schema');

try {
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
