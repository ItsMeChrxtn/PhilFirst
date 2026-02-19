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

// Resolve user and ensure admin
$userRole = null;
if (!empty($_SESSION['user'])){
    $u = $_SESSION['user'];
    if (is_array($u)){
        $userRole = $u['role'] ?? null;
    }
}
if (empty($userRole) || strtolower($userRole) !== 'admin'){
    // require admin for full analytics
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit;
}

try {
    // Overall counts (global)
    $sqlCounts = "SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN LOWER(status) IN ('approved','for interview') THEN 1 ELSE 0 END) AS for_interview,
        SUM(CASE WHEN LOWER(status) = 'accepted' THEN 1 ELSE 0 END) AS accepted,
        SUM(CASE WHEN LOWER(status) IN ('rejected','declined') THEN 1 ELSE 0 END) AS rejected
        FROM applications";
    $stmt = $pdo->prepare($sqlCounts);
    $stmt->execute();
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Year selection (for charts) - defaults to current year
    $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
    $pie_year = isset($_GET['pie_year']) ? (int)$_GET['pie_year'] : $year;
    $pie_month = isset($_GET['pie_month']) ? (int)$_GET['pie_month'] : 0; // 0 = whole year

    // Monthly counts for the selected year (Jan..Dec)
    $sqlMonthly = "SELECT MONTH(created_at) AS m, COUNT(*) AS cnt
        FROM applications
        WHERE YEAR(created_at) = :y
        GROUP BY m
        ORDER BY m";
    $stmt = $pdo->prepare($sqlMonthly);
    $stmt->execute([':y' => $year]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build Jan..Dec labels and map counts
    $labels = [];
    $data = [];
    $map = [];
    for ($m = 1; $m <= 12; $m++){
        $labels[] = DateTime::createFromFormat('!m', $m)->format('M');
        $map[$m] = 0;
    }
    foreach ($rows as $r){
        $mi = (int)$r['m'];
        if (isset($map[$mi])) $map[$mi] = (int)$r['cnt'];
    }
    for ($m = 1; $m <= 12; $m++) $data[] = $map[$m];

    // Pie chart counts for accepted vs rejected for selected pie_year
    // build pie SQL with optional month filter
    $sqlPie = "SELECT 
        SUM(CASE WHEN LOWER(status) = 'accepted' THEN 1 ELSE 0 END) AS accepted,
        SUM(CASE WHEN LOWER(status) IN ('rejected','declined') THEN 1 ELSE 0 END) AS rejected
        FROM applications
        WHERE YEAR(created_at) = :py";
    $pieParams = [':py' => $pie_year];
    if ($pie_month >= 1 && $pie_month <= 12) {
        $sqlPie .= " AND MONTH(created_at) = :pm";
        $pieParams[':pm'] = $pie_month;
    }
    $stmt = $pdo->prepare($sqlPie);
    $stmt->execute($pieParams);
    $pieCounts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Top jobs by application count for the selected year (default top 10)
    $topN = isset($_GET['top']) ? (int)$_GET['top'] : 10;
    if ($topN <= 0) $topN = 10;
    $topN = min(100, $topN);
    $top_year = isset($_GET['top_year']) ? (int)$_GET['top_year'] : $year;
    $top_month = isset($_GET['top_month']) ? (int)$_GET['top_month'] : 0; // 0 = whole year

    // PDO / MySQL may not accept bound parameters for LIMIT in all configurations,
    // so inject a sanitized integer directly into the SQL string. Support optional month filter.
    $sqlTop = "SELECT a.job_id, COALESCE(jp.title, '') AS title, COUNT(*) AS cnt
        FROM applications a
        LEFT JOIN job_postings jp ON a.job_id = jp.id
        WHERE YEAR(a.created_at) = :ty";
    $topParams = [':ty' => $top_year];
    if ($top_month >= 1 && $top_month <= 12) {
        $sqlTop .= " AND MONTH(a.created_at) = :tm";
        $topParams[':tm'] = $top_month;
    }
    $sqlTop .= " GROUP BY a.job_id
        ORDER BY cnt DESC
        LIMIT " . (int)$topN;
    $stmt = $pdo->prepare($sqlTop);
    $stmt->execute($topParams);
    $topRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $topJobs = [];
    foreach ($topRows as $r) {
        $topJobs[] = ['job_id' => $r['job_id'], 'title' => $r['title'], 'count' => (int)$r['cnt']];
    }

    echo json_encode([
        'success' => true,
        'counts' => $counts,
        'monthly' => [ 'labels' => $labels, 'data' => $data, 'year' => $year ],
        'pie' => [ 'labels' => ['Accepted','Rejected'], 'data' => [(int)$pieCounts['accepted'], (int)$pieCounts['rejected']], 'year' => $pie_year ],
        // Top jobs by application count for the selected year
        'top_jobs' => $topJobs
    ]);
} catch (Exception $e) {
    @file_put_contents(__DIR__ . '/error.log', date('c') . ' analytics error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error', 'error' => $e->getMessage()]);
}

?>
