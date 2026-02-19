<?php
// Destroys the session and logs the user out.
session_start();

// clear session array
$_SESSION = [];

// remove session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
}

// destroy session
session_destroy();

// If request is XHR, return JSON; otherwise redirect back (referer) or to frontend index
$isXhr = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($isXhr) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true]);
    exit;
}

$ref = $_SERVER['HTTP_REFERER'] ?? null;
if ($ref) {
    header('Location: ' . $ref);
} else {
    header('Location: ../index.php');
}
exit;
