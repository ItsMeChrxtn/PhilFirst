<?php
header('Content-Type: application/json; charset=utf-8');

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

if (empty($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'valid' => false, 'message' => 'Resume/Biodata file is required']);
    exit;
}

$orig = basename($_FILES['resume']['name']);
$origLower = strtolower($orig);
if (!preg_match('/(resume|bio\s*data|biodata)/i', $origLower)) {
    echo json_encode(['success' => false, 'valid' => false, 'message' => 'Invalid file name. Filename must contain resume or biodata']);
    exit;
}

$ext = pathinfo($orig, PATHINFO_EXTENSION);
$extLower = strtolower($ext);
$allowedExt = ['pdf', 'doc', 'docx'];
if (!in_array($extLower, $allowedExt, true)) {
    echo json_encode(['success' => false, 'valid' => false, 'message' => 'Invalid file type. Allowed: PDF, DOC, DOCX']);
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
        echo json_encode(['success' => false, 'valid' => false, 'message' => 'Invalid document content. Please upload a valid PDF/DOC/DOCX resume']);
        exit;
    }
}

$extractedText = extract_resume_text($_FILES['resume']['tmp_name'], $extLower);
$result = validate_resume_indicators($_FILES['resume']['tmp_name'], $extLower, $extractedText);

if (!$result['valid']) {
    echo json_encode([
        'success' => false,
        'valid' => false,
        'message' => 'Invalid file: walang nakita na name, experience, resume/biodata details, o profile picture.'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'valid' => true,
    'message' => 'Valid resume/biodata detected',
    'checks' => [
        'name' => $result['nameFound'],
        'experience' => $result['experienceFound'],
        'keywords' => $result['keywordFound'],
        'photo' => $result['photoFound']
    ]
]);
