<?php
// Handle video uploads
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['video'])) {
    echo json_encode(['success' => false, 'error' => 'No video file provided']);
    exit;
}

$file = $_FILES['video'];
$max_size = 500 * 1024 * 1024; // 500MB
$allowed_types = ['video/mp4', 'video/webm', 'video/ogg'];
$upload_dir = __DIR__ . '/../uploads/videos/';

// Create directory if doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Validate file
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Upload error: ' . $file['error']]);
    exit;
}

if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'error' => 'File too large. Max size: 500MB']);
    exit;
}

if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Allowed: MP4, WebM, OGG']);
    exit;
}

// Generate unique filename
$filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
$filepath = $upload_dir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    $video_url = '/uploads/videos/' . $filename;
    echo json_encode([
        'success' => true,
        'message' => 'Video uploaded successfully',
        'url' => $video_url,
        'filename' => $filename
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save video file']);
}
?>
