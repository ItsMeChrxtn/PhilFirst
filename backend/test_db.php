<?php
header('Content-Type: application/json; charset=utf-8');
// Simple tester to verify PDO connection using existing config.php
try{
    require_once __DIR__ . '/config.php';
    $stmt = $pdo->query('SELECT NOW() AS now');
    $row = $stmt->fetch();
    echo json_encode(['success'=>true,'now'=>$row['now'] ?? null]);
} catch (Throwable $e) {
    $errFile = __DIR__ . '/error.log';
    $msg = date('c') . ' test_db error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    @file_put_contents($errFile, $msg, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'DB connection failed','error'=>$e->getMessage()]);
}

?>
