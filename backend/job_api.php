<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
// Simple job postings API (list, create, get, update, delete)
// Adjust or secure as needed before production use.

// Use shared DB config
try {
    require_once __DIR__ . '/config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

$action = $_REQUEST['action'] ?? 'list';

function jsonOut($data) {
    echo json_encode($data);
    exit;
}

if ($action === 'list') {
    $province = $_GET['province'] ?? null;
    if ($province) {
        $stmt = $pdo->prepare('SELECT * FROM job_postings WHERE location LIKE ? ORDER BY created_at DESC');
        $stmt->execute(["%$province%"]);
    } else {
        $stmt = $pdo->query('SELECT * FROM job_postings ORDER BY created_at DESC');
    }
    $rows = $stmt->fetchAll();
    jsonOut(['success' => true, 'data' => $rows]);
}

if ($action === 'provinces') {
    // Extract the province (last segment after comma) from location values
    $stmt = $pdo->query("SELECT DISTINCT TRIM(SUBSTRING_INDEX(location, ',', -1)) AS province FROM job_postings WHERE location IS NOT NULL AND TRIM(location) <> '' ORDER BY province");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $provinces = array_values(array_filter(array_map(function($r){ return $r['province'] ?? null; }, $rows)));
    jsonOut(['success' => true, 'data' => $provinces]);
}

if ($action === 'get') {
    $id = $_GET['id'] ?? null;
    if (!$id) jsonOut(['success' => false, 'message' => 'Missing id']);
    $stmt = $pdo->prepare('SELECT * FROM job_postings WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    jsonOut(['success' => true, 'data' => $row]);
}

if ($action === 'create') {
    $data = $_POST;
    $sql = 'INSERT INTO job_postings (title, client, location, status, job_type, skills, salary, qualifications, benefits, job_description, created_at, updated_at) VALUES (:title,:client,:location,:status,:job_type,:skills,:salary,:qualifications,:benefits,:job_description,NOW(),NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $data['title'] ?? '',
        ':client' => $data['client'] ?? '',
        ':location' => $data['location'] ?? '',
        ':status' => $data['status'] ?? 'Active',
        ':job_type' => $data['job_type'] ?? '',
        ':skills' => $data['skills'] ?? '',
        ':salary' => $data['salary'] ?? '',
        ':qualifications' => $data['qualifications'] ?? '',
        ':benefits' => $data['benefits'] ?? '',
        ':job_description' => $data['job_description'] ?? '',
    ]);
    jsonOut(['success' => true, 'message' => 'Created successfully', 'id' => $pdo->lastInsertId()]);
}

if ($action === 'update') {
    $data = $_POST;
    if (empty($data['id'])) jsonOut(['success' => false, 'message' => 'Missing id']);
    $sql = 'UPDATE job_postings SET title=:title, client=:client, location=:location, status=:status, job_type=:job_type, skills=:skills, salary=:salary, qualifications=:qualifications, benefits=:benefits, job_description=:job_description, updated_at=NOW() WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $data['title'] ?? '',
        ':client' => $data['client'] ?? '',
        ':location' => $data['location'] ?? '',
        ':status' => $data['status'] ?? 'Active',
        ':job_type' => $data['job_type'] ?? '',
        ':skills' => $data['skills'] ?? '',
        ':salary' => $data['salary'] ?? '',
        ':qualifications' => $data['qualifications'] ?? '',
        ':benefits' => $data['benefits'] ?? '',
        ':job_description' => $data['job_description'] ?? '',
        ':id' => $data['id'],
    ]);
    jsonOut(['success' => true, 'message' => 'Updated successfully']);
}

if ($action === 'delete') {
    $id = $_POST['id'] ?? null;
    if (!$id) jsonOut(['success' => false, 'message' => 'Missing id']);
    $stmt = $pdo->prepare('DELETE FROM job_postings WHERE id = ?');
    $stmt->execute([$id]);
    jsonOut(['success' => true, 'message' => 'Deleted successfully']);
}

jsonOut(['success' => false, 'message' => 'Unknown action']);
