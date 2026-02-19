<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

// Check if user is admin
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once 'config.php';
require_once 'cms_helper.php';

$cms = new CMSHelper($pdo);
$action = isset($_GET['action']) ? $_GET['action'] : '';
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if ($action === 'list') {
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;
            
            $pages = $cms->getAllPages($status, $limit, $offset);
            $total = $cms->getTotalPageCount($status);
            
            echo json_encode([
                'success' => true,
                'data' => $pages,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]);
        } elseif ($action === 'get') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $page = $cms->getPageById($id);
            
            if ($page) {
                echo json_encode(['success' => true, 'data' => $page]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Page not found']);
            }
        } elseif ($action === 'get-by-slug') {
            $slug = isset($_GET['slug']) ? $_GET['slug'] : '';
            $page = $cms->getPageBySlug($slug);
            
            if ($page) {
                echo json_encode(['success' => true, 'data' => $page]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Page not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if ($action === 'create') {
            $title = isset($input['title']) ? trim($input['title']) : '';
            $slug = isset($input['slug']) ? trim($input['slug']) : '';
            $content = isset($input['content']) ? $input['content'] : '';
            $status = isset($input['status']) ? $input['status'] : 'draft';
            
            if (empty($title) || empty($content)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Title and content are required']);
                exit;
            }
            
            $author_id = $_SESSION['user']['id'] ?? null;
            $id = $cms->createPage($title, $slug, $content, $status, $author_id);
            
            if ($id) {
                echo json_encode(['success' => true, 'id' => $id, 'message' => 'Page created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to create page']);
            }
        } elseif ($action === 'update') {
            $id = isset($input['id']) ? (int)$input['id'] : 0;
            $title = isset($input['title']) ? trim($input['title']) : '';
            $slug = isset($input['slug']) ? trim($input['slug']) : '';
            $content = isset($input['content']) ? $input['content'] : '';
            $status = isset($input['status']) ? $input['status'] : 'draft';
            
            if (!$id || empty($title) || empty($content)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID, title and content are required']);
                exit;
            }
            
            if ($cms->updatePage($id, $title, $slug, $content, $status)) {
                echo json_encode(['success' => true, 'message' => 'Page updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to update page']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } elseif ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if ($action === 'delete') {
            $id = isset($input['id']) ? (int)$input['id'] : 0;
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID is required']);
                exit;
            }
            
            if ($cms->deletePage($id)) {
                echo json_encode(['success' => true, 'message' => 'Page deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to delete page']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
