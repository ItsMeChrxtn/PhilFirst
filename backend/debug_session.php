<?php
// Simple debug endpoint to inspect PHP session state. Remove when done.
header('Content-Type: application/json; charset=utf-8');
session_start();
$out = [
    'session_name' => session_name(),
    'session_id' => session_id(),
    'session_cookie_present' => isset($_COOKIE[session_name()]) ? $_COOKIE[session_name()] : null,
    'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
    'full_session' => $_SESSION
];
// Avoid exposing sensitive data in production. This is for local debugging only.
echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
