<?php
// Shared DB configuration and PDO factory for backend scripts
// Update these values once and all backend PHP files will use them.

$DB_HOST = 'localhost';
$DB_NAME = 'u276626368_PhilFirst_db';
$DB_USER = 'u276626368_PhilFirst';
$DB_PASS = 'PhilFirst@2026';

// create PDO and expose as $pdo and $DB_NAME
try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    // For scripts that include this file, we don't exit here; let callers handle exceptions.
    throw $e;
}

?>
