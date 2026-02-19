<?php
// CMS Helper Functions for managing content pages
require_once 'config.php';

class CMSHelper {
    private $pdo;
    private $table = 'cms_pages';

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->initializeTable();
    }

    /**
     * Initialize the cms_pages table if it doesn't exist
     */
    private function initializeTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content LONGTEXT NOT NULL,
            status ENUM('published', 'draft') DEFAULT 'draft',
            author_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (slug),
            INDEX (status),
            INDEX (created_at)
        )";
        try {
            $this->pdo->exec($sql);
        } catch (Exception $e) {
            // Table might already exist
        }
    }

    /**
     * Get all pages with optional filtering
     */
    public function getAllPages($status = null, $limit = null, $offset = 0) {
        $sql = "SELECT id, title, slug, content, status, author_id, created_at, updated_at FROM {$this->table}";
        
        if ($status) {
            $sql .= " WHERE status = :status";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get a single page by ID
     */
    public function getPageById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Get a page by slug
     */
    public function getPageBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create a new page
     */
    public function createPage($title, $slug, $content, $status = 'draft', $author_id = null) {
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = $this->generateSlug($title);
        }

        $sql = "INSERT INTO {$this->table} (title, slug, content, status, author_id) 
                VALUES (:title, :slug, :content, :status, :author_id)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Update a page
     */
    public function updatePage($id, $title, $slug, $content, $status) {
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = $this->generateSlug($title);
        }

        $sql = "UPDATE {$this->table} SET title = :title, slug = :slug, content = :content, status = :status 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':status', $status);
        
        return $stmt->execute();
    }

    /**
     * Delete a page
     */
    public function deletePage($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Generate a URL-friendly slug from title
     */
    private function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug already exists and append number if needed
        $original_slug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Check if slug already exists
     */
    private function slugExists($slug) {
        $sql = "SELECT id FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Get total page count
     */
    public function getTotalPageCount($status = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if ($status) {
            $sql .= " WHERE status = :status";
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
}

?>
