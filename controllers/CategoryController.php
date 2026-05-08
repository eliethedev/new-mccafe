<?php

class CategoryController extends Controller {
    
    public function index(Request $request) {
        // Get all categories with product counts
        $categories = $this->getCategoriesWithCounts();
        
        return $this->view('admin/categories/index', [
            'categories' => $categories
        ]);
    }
    
    public function store(Request $request) {
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:100',
            'description' => 'max:500',
            'sort_order' => 'integer|min:0'
        ]);
        
        if (!empty($errors)) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'errors' => $errors]), 400);
            }
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect('/admin/categories');
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image'], 'categories');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['filename'];
            } else {
                if ($request->isAjax()) {
                    return new Response(json_encode(['success' => false, 'message' => $uploadResult['message']]), 400);
                }
                Session::flash('error', $uploadResult['message']);
                Session::flash('old', $data);
                return $this->redirect('/admin/categories');
            }
        }
        
        // Create category
        $categoryId = $this->createCategory($data);
        
        if ($categoryId) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true, 'category_id' => $categoryId]));
            }
            Session::flash('success', 'Category created successfully!');
            return $this->redirect('/admin/categories');
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to create category']), 500);
            }
            Session::flash('error', 'Failed to create category. Please try again.');
            return $this->redirect('/admin/categories');
        }
    }
    
    public function edit(Request $request, $id) {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Category not found']), 404);
            }
            Session::flash('error', 'Category not found');
            return $this->redirect('/admin/categories');
        }
        
        if ($request->isAjax()) {
            return new Response(json_encode(['success' => true, 'category' => $category]));
        }
        
        $productCount = $this->getProductCountByCategory($id);
        
        return $this->view('admin/categories/edit', [
            'category' => $category,
            'productCount' => $productCount
        ]);
    }
    
    public function update(Request $request, $id) {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Category not found']), 404);
            }
            Session::flash('error', 'Category not found');
            return $this->redirect('/admin/categories');
        }
        
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:100',
            'description' => 'max:500',
            'sort_order' => 'integer|min:0'
        ]);
        
        if (!empty($errors)) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'errors' => $errors]), 400);
            }
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect("/admin/categories/{$id}/edit");
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image'], 'categories');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['filename'];
                // Delete old image if exists
                if (!empty($category['image'])) {
                    $this->deleteImage($category['image'], 'categories');
                }
            } else {
                if ($request->isAjax()) {
                    return new Response(json_encode(['success' => false, 'message' => $uploadResult['message']]), 400);
                }
                Session::flash('error', $uploadResult['message']);
                Session::flash('old', $data);
                return $this->redirect("/admin/categories/{$id}/edit");
            }
        }
        
        // Update category
        $success = $this->updateCategory($id, $data);
        
        if ($success) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true]));
            }
            Session::flash('success', 'Category updated successfully!');
            return $this->redirect('/admin/categories');
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to update category']), 500);
            }
            Session::flash('error', 'Failed to update category. Please try again.');
            return $this->redirect("/admin/categories/{$id}/edit");
        }
    }
    
    public function delete(Request $request, $id) {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Category not found']), 404);
            }
            Session::flash('error', 'Category not found');
            return $this->redirect('/admin/categories');
        }
        
        // Check if category has products
        $productCount = $this->getProductCountByCategory($id);
        if ($productCount > 0) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Cannot delete category with associated products']), 400);
            }
            Session::flash('error', 'Cannot delete category with associated products');
            return $this->redirect('/admin/categories');
        }
        
        // Delete category
        $success = $this->deleteCategory($id);
        
        if ($success) {
            // Delete image if exists
            if (!empty($category['image'])) {
                $this->deleteImage($category['image'], 'categories');
            }
            
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true]));
            }
            Session::flash('success', 'Category deleted successfully!');
            return $this->redirect('/admin/categories');
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to delete category']), 500);
            }
            Session::flash('error', 'Failed to delete category. Please try again.');
            return $this->redirect('/admin/categories');
        }
    }
    
    public function updateStatus(Request $request, $id) {
        $category = $this->getCategoryById($id);
        
        if (!$category) {
            return new Response(json_encode(['success' => false, 'message' => 'Category not found']), 404);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $isActive = $data['is_active'] ?? false;
        
        $success = $this->updateCategoryStatus($id, $isActive);
        
        if ($success) {
            return new Response(json_encode(['success' => true]));
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Failed to update status']), 500);
        }
    }
    
    // Helper methods
    private function getCategoriesWithCounts() {
        $stmt = $this->query("
            SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id 
            ORDER BY c.sort_order ASC, c.name ASC
        ");
        return $stmt->fetchAll();
    }
    
    private function getCategoryById($id) {
        $stmt = $this->query("SELECT * FROM categories WHERE id = ?", [$id]);
        return $stmt->fetch();
    }
    
    private function createCategory($data) {
        $sql = "INSERT INTO categories (name, description, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $data['name'],
            $data['description'] ?? null,
            $data['image'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_active'] ?? 1
        ];
        
        $stmt = $this->query($sql, $params);
        return $this->lastInsertId();
    }
    
    private function updateCategory($id, $data) {
        $sql = "UPDATE categories SET name = ?, description = ?, sort_order = ?, is_active = ?";
        $params = [
            $data['name'],
            $data['description'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_active'] ?? 1
        ];
        
        if (isset($data['image'])) {
            $sql .= ", image = ?";
            $params[] = $data['image'];
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    private function updateCategoryStatus($id, $isActive) {
        $stmt = $this->query("UPDATE categories SET is_active = ? WHERE id = ?", [$isActive ? 1 : 0, $id]);
        return $stmt->rowCount() > 0;
    }
    
    private function deleteCategory($id) {
        $stmt = $this->query("DELETE FROM categories WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }
    
    private function getProductCountByCategory($id) {
        $stmt = $this->query("SELECT COUNT(*) as count FROM products WHERE category_id = ?", [$id]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    private function handleImageUpload($file, $folder) {
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Only JPG, PNG, and GIF files are allowed'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File size must be less than 5MB'];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        
        // Create upload directory
        $uploadDir = PUBLIC_PATH . "/assets/images/{$folder}/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move file
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'message' => 'Failed to upload file'];
        }
    }
    
    private function deleteImage($filename, $folder) {
        $filepath = PUBLIC_PATH . "/assets/images/{$folder}/{$filename}";
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return true;
    }
    
    private function query($sql, $params = []) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    private function lastInsertId() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
}
