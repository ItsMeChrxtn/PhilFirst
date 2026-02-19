<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';

try {
    // Get the latest update timestamp from cms_pages
    $stmt = $pdo->query("SELECT MAX(updated_at) as last_update FROM cms_pages WHERE status = 'published'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $lastUpdate = $result['last_update'] ?? date('Y-m-d H:i:s');
    
    echo json_encode([
        'success' => true,
        'last_update' => strtotime($lastUpdate),
        'timestamp' => $lastUpdate
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to check updates'
    ]);
}
?>
