<?php

class ProductController extends Controller {
    
    public function index() {
        $search = $_GET['search'] ?? null;
        $categoryFilter = $_GET['category'] ?? null;

        $categories = Product::getCategories();
        $menuCategories = [];

        foreach ($categories as $cat) {
            $products = Product::getByCategory($cat['name'], 1, 100);
            $items = [];

            foreach ($products as $productIdx => $product) {
                $variantStmt = self::query(
                    "SELECT * FROM product_variants WHERE product_id = ? AND is_available = 1 ORDER BY sort_order ASC",
                    [$product['id']]
                );
                $variants = $variantStmt->fetchAll();

                $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($product['name']));
                $slug = trim($slug, '-') ?: 'item' . $product['id'];
                $quantityId = 'quantity-' . $slug;
                $sizeId = $variants ? 'size-' . $slug : '';

                $options = [];
                $priceParts = [];
                foreach ($variants as $v) {
                    $price = (float)$product['price'] + (float)$v['price_adjustment'];
                    $options[] = [
                        'value' => (string)$price,
                        'label' => $v['name'] . ' - ₱' . $price,
                    ];
                    $priceParts[] = '₱' . $price . ' ' . $v['name'];
                }

                $cartName = $product['name'];
                if (count($variants) === 1 && $variants[0]['name'] === 'Default') {
                    $price = (float)$product['price'] + (float)$variants[0]['price_adjustment'];
                    $options = [];
                    $priceParts = ['₱' . $price];
                    $cartName = $product['name'];
                }

                if (empty($options)) {
                    $startPrice = (string)$product['price'];
                } else {
                    $startPrice = (string)$options[0]['value'];
                }

                $items[] = [
                    'id' => $product['id'],
                    'displayName' => $product['name'],
                    'image' => $product['image'] ? '/assets/images/products/' . $product['image'] : '/assets/images/products/placeholder.jpg',
                    'priceText' => 'Price: ' . implode(' ┃ ', $priceParts ?: ['₱' . $product['price']]),
                    'quantityId' => $quantityId,
                    'sizeId' => $sizeId,
                    'options' => $options,
                    'coffeeOptions' => [],
                    'cartCall' => [
                        'name' => $cartName,
                        'quantityId' => $quantityId,
                        'sizeId' => $sizeId,
                        'fixedPrice' => empty($options) ? (float)$product['price'] : null,
                    ],
                    'detail' => [
                        'name' => $product['name'],
'image' => $product['image'] ? '/assets/images/products/' . $product['image'] : '/assets/images/products/placeholder.jpg',
                        'description' => $product['description'] ?: 'No description available.',
                        'category' => $cat['name'],
                        'time' => $product['preparation_time'] ? $product['preparation_time'] . 'min' : '5min',
                        'price' => $startPrice,
                    ],
                ];
            }

            $menuCategories[] = [
                'id' => 'cat-' . $cat['id'],
                'name' => $cat['name'],
                'items' => $items,
            ];
        }


        // Build best seller and featured item collections (DB-driven)
        $transformToMenuItem = function (array $products): array {
            $items = [];
            foreach ($products as $product) {
                $variantStmt = self::query(
                    'SELECT * FROM product_variants WHERE product_id = ? AND is_available = 1 ORDER BY sort_order ASC',
                    [$product['id']]
                );
                $variants = $variantStmt->fetchAll();

                $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower((string)$product['name'])), '-') ?: 'item' . $product['id'];
                $quantityId = 'quantity-' . $slug;
                $sizeId = $variants ? 'size-' . $slug : '';

                $options = [];
                $priceParts = [];
                foreach ($variants as $v) {
                    $price = (float)$product['price'] + (float)$v['price_adjustment'];
                    $options[] = [
                        'value' => (string)$price,
                        'label' => $v['name'] . ' - ₱' . $price,
                    ];
                    $priceParts[] = '₱' . $price . ' ' . $v['name'];
                }

                $cartName = $product['name'];
                if (count($variants) === 1 && isset($variants[0]['name']) && $variants[0]['name'] === 'Default') {
                    $price = (float)$product['price'] + (float)$variants[0]['price_adjustment'];
                    $options = [];
                    $priceParts = ['₱' . $price];
                }

                if (empty($options)) {
                    $startPrice = (string)$product['price'];
                } else {
                    $startPrice = (string)$options[0]['value'];
                }

                $image = $product['image'] ? '/assets/images/products/' . $product['image'] : '/assets/images/products/placeholder.jpg';

                $items[] = [
                    'id' => $product['id'],
                    'displayName' => $product['name'],
                    'image' => $image,
                    'priceText' => 'Price: ' . implode(' ┃ ', $priceParts ?: ['₱' . $product['price']]),
                    'quantityId' => $quantityId,
                    'sizeId' => $sizeId,
                    'options' => $options,
                    'coffeeOptions' => [],
                    'cartCall' => [
                        'name' => $cartName,
                        'quantityId' => $quantityId,
                        'sizeId' => $sizeId,
                        'fixedPrice' => empty($options) ? (float)$product['price'] : null,
                    ],
                    'detail' => [
                        'name' => $product['name'],
                        'image' => $image,
                        'description' => $product['description'] ?: 'No description available.',
                        'category' => isset($product['category_name']) ? $product['category_name'] : 'Best sellers',
                        'time' => $product['preparation_time'] ? $product['preparation_time'] . 'min' : '5min',
                        'price' => $startPrice,
                    ],
                ];
            }
            return $items;
        };

        $bestSellerItems = $transformToMenuItem(Product::getFlaggedBestSellers(8));
        $featuredItems = $transformToMenuItem(Product::getFeaturedItems(8));

        $products = Product::getAllAvailable(1, 100);
        $totalCount = Product::getTotalCount();

        return $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'menuCategories' => $menuCategories,
            'bestSellerItems' => $bestSellerItems,
            'featuredItems' => $featuredItems,
            'currentPage' => 1,
            'totalPages' => 1,
            'currentCategory' => $categoryFilter,
            'searchQuery' => $search,
        ]);
    }
    
    public function fullMenu() {
        // Legacy static menu retired: the live CafeCat menu is DB-driven at /menu
        return $this->redirect('/menu');
    }
    
    public function category($request, $category) {
        $_GET['category'] = $category;
        return $this->index();
    }
    
    public function subcategory($request, $mainCategory, $subCategory) {
        $_GET['category'] = $mainCategory;
        $_GET['subcategory'] = $subCategory;
        return $this->index();
    }
    
    public function show($request, $id) {
        $product = Product::getWithVariants($id);
        
        if (!$product) {
            return $this->redirect('/menu');
        }
        
        // Get related products
        $relatedProducts = Product::getByCategory($product['category_name'], 1, 4);
        
        // Get categories for navigation
        $categories = Product::getCategories();
        $currentCategory = $product['category_name'];
        
        return $this->view('products/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'categories' => $categories,
            'currentCategory' => $currentCategory
        ]);
    }
    
    public function adminIndex() {
        $page = $_GET['page'] ?? 1;
        $category = $_GET['category'] ?? null;
        $availability = $_GET['availability'] ?? null;
        $search = $_GET['search'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $flag = $_GET['flag'] ?? null; // bestseller | featured | unavailable
        
        if ($flag === 'unavailable' && !$availability) {
            $availability = 'unavailable';
        }

        $products = Product::getAdminProducts($page, $category, $availability, $search, $sort, $flag);
        $totalCount = Product::getAdminTotalCount($category, $availability, $search, $flag);
        $totalPages = ceil($totalCount / 20);
        $categories = Product::getCategories();
        
        return $this->view('admin/products/index', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'currentCategory' => $category,
            'searchQuery' => $search,
            'currentAvailability' => $availability,
            'currentSort' => $sort,
            'currentFlag' => $flag,
            'menuStats' => Product::getMenuStats(),
            'title' => 'Menu Management - CafeCat',
        ]);
    }
    
    public function create() {
        $categories = Product::getCategories();
        
        return $this->view('admin/products/create', [
            'categories' => $categories
        ]);
    }
    
    public function store(Request $request) {
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'category_id' => 'required',
            'name' => 'required|min:2',
            'description' => 'optional',
            'price' => 'required'
        ]);
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect('/admin/products/create');
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['filename'];
            } else {
                Session::flash('error', $uploadResult['message']);
                Session::flash('old', $data);
                return $this->redirect('/admin/products/create');
            }
        }

        // Checkbox flags (unchecked boxes are absent from POST)
        $data['is_available'] = isset($data['is_available']) ? 1 : 0;
        $data['is_bestseller'] = isset($data['is_bestseller']) ? 1 : 0;
        $data['is_featured'] = isset($data['is_featured']) ? 1 : 0;

        $productId = Product::create($data);
        
        if ($productId) {
            Session::flash('success', 'Product created successfully!');
            return $this->redirect('/admin/products');
        } else {
            Session::flash('error', 'Failed to create product. Please try again.');
            return $this->redirect('/admin/products/create');
        }
    }
    
    public function edit($request, $id) {
        $product = Product::find($id);
        $categories = Product::getCategories();
        
        if (!$product) {
            Session::flash('error', 'Product not found');
            return $this->redirect('/admin/products');
        }
        
        return $this->view('admin/products/edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }
    
    public function update(Request $request, $id) {
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'category_id' => 'optional',
            'name' => 'optional',
            'description' => 'optional',
            'price' => 'optional'
        ]);
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect("/admin/products/$id/edit");
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['filename'];
            } else {
                Session::flash('error', $uploadResult['message']);
                Session::flash('old', $data);
                return $this->redirect("/admin/products/$id/edit");
            }
        }

        // Checkbox flags
        $data['is_available'] = isset($data['is_available']) ? 1 : 0;
        $data['is_bestseller'] = isset($data['is_bestseller']) ? 1 : 0;
        $data['is_featured'] = isset($data['is_featured']) ? 1 : 0;
        
        $success = Product::update($id, $data);
        
        if ($success) {
            Session::flash('success', 'Product updated successfully!');
            return $this->redirect('/admin/products');
        } else {
            Session::flash('error', 'Failed to update product. Please try again.');
            return $this->redirect("/admin/products/$id/edit");
        }
    }
    
    public function delete(Request $request, $id) {
        $product = Product::find($id);
        
        if (!$product) {
            return $this->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        $success = Product::delete($id);
        
        if ($success) {
            // Delete product image if exists
            if ($product['image']) {
                $imagePath = PUBLIC_PATH . '/assets/images/products/' . $product['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            return $this->json(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            return $this->json(['success' => false, 'message' => 'Failed to delete product'], 500);
        }
    }
    
    private function handleImageUpload($file) {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Check file type
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'];
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.'];
        }
        
        // Create upload directory if it doesn't exist
        $uploadDir = PUBLIC_PATH . '/assets/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $filename = uniqid('product_', true) . '.' . $fileType;
        $uploadPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'message' => 'Failed to upload file.'];
        }
    }
    
    public function updateStatus(Request $request, $id) {
        $product = Product::find($id);
        
        if (!$product) {
            return new Response(json_encode(['success' => false, 'message' => 'Product not found']), 404);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $isAvailable = $data['is_available'] ?? false;
        
        $success = Product::update($id, ['is_available' => $isAvailable ? 1 : 0]);
        
        if ($success) {
            return new Response(json_encode(['success' => true]));
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Failed to update status']), 500);
        }
    }
    
    public function updateSortOrder(Request $request, $id) {
        $product = Product::find($id);
        if (!$product) {
            return new Response(json_encode(['success' => false, 'message' => 'Product not found']), 404);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $sortOrder = $data['sort_order'] ?? 0;
        
        $success = Product::update($id, ['sort_order' => $sortOrder]);
        
        if ($success) {
            return new Response(json_encode(['success' => true]));
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Failed to update sort order']), 500);
        }
    }

    private function toggleFlag(Request $request, $id, $field) {
        $product = Product::find($id);
        if (!$product) {
            $r = new Response(json_encode(['success' => false, 'message' => 'Product not found']), 404);
            $r->setHeader('Content-Type', 'application/json');
            return $r;
        }
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $value = $data['value'] ?? null;
        if ($value === null) {
            $value = empty($product[$field]) ? 1 : 0;
        } else {
            $value = $value ? 1 : 0;
        }
        Product::update($id, [$field => $value]);
        $r = new Response(json_encode(['success' => true, 'value' => $value]));
        $r->setHeader('Content-Type', 'application/json');
        return $r;
    }

    public function toggleBestseller(Request $request, $id) {
        return $this->toggleFlag($request, $id, 'is_bestseller');
    }

    public function toggleFeatured(Request $request, $id) {
        return $this->toggleFlag($request, $id, 'is_featured');
    }
    
    public function variants(Request $request, $id) {
        $product = Product::find($id);
        
        if (!$product) {
            Session::flash('error', 'Product not found');
            return $this->redirect('/admin/products');
        }
        
        $variants = $this->getProductVariants($id);
        
        return $this->view('admin/products/variants', [
            'product' => $product,
            'variants' => $variants
        ]);
    }
    
    public function storeVariant(Request $request, $id) {
        $product = Product::find($id);
        
        if (!$product) {
            return new Response(json_encode(['success' => false, 'message' => 'Product not found']), 404);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (empty($data['name']) || !isset($data['price_adjustment'])) {
            return new Response(json_encode(['success' => false, 'message' => 'Name and price adjustment are required']), 400);
        }
        
        $success = $this->createProductVariant($id, $data);
        
        if ($success) {
            return new Response(json_encode(['success' => true]));
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Failed to create variant']), 500);
        }
    }
    
    public function updateVariantStatus(Request $request, $id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $isAvailable = $data['is_available'] ?? false;
        
        $success = $this->updateVariantStatusHelper($id, $isAvailable ? 1 : 0);
        
        if ($success) {
            return new Response(json_encode(['success' => true]));
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Failed to update variant status']), 500);
        }
    }
    
    public function deleteVariant(Request $request, $id) {
        $success = $this->deleteProductVariant($id);
        
        if ($success) {
            return new Response(json_encode(['success' => true]));
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Failed to delete variant']), 500);
        }
    }
    
    // Helper methods for variants
    private function getProductVariants($productId) {
        $stmt = $this->query("SELECT * FROM product_variants WHERE product_id = ? ORDER BY sort_order ASC, name ASC", [$productId]);
        return $stmt->fetchAll();
    }
    
    private function createProductVariant($productId, $data) {
        $sql = "INSERT INTO product_variants (product_id, name, price_adjustment, is_available) VALUES (?, ?, ?, ?)";
        $params = [
            $productId,
            $data['name'],
            $data['price_adjustment'],
            $data['is_available'] ?? 1
        ];
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    private function updateVariantStatusHelper($id, $isAvailable) {
        $stmt = $this->query("UPDATE product_variants SET is_available = ? WHERE id = ?", [$isAvailable, $id]);
        return $stmt->rowCount() > 0;
    }
    
    private function deleteProductVariant($id) {
        $stmt = $this->query("DELETE FROM product_variants WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
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
}
