<?php

class UserController extends Controller {
    public function dashboard(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        return $this->view('user/dashboard', [
            'title' => 'Dashboard'
        ]);
    }
    
    public function profile(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        return $this->view('user/profile', [
            'title' => 'My Profile'
        ]);
    }
    
    public function updateProfile(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        if ($request->isPost()) {
            $data = $request->getBody();
            
            // Validate input
            $errors = $this->validate($data, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email'
            ]);
            
            if (!empty($errors)) {
                $_SESSION['error_message'] = 'Validation failed. Please check your input.';
                header('Location: /profile');
                exit;
            }
            
            // Check if email is already taken by another user
            $existingUser = User::findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                $_SESSION['error_message'] = 'Email is already taken by another user.';
                header('Location: /profile');
                exit;
            }
            
            // Update user profile
            $updateData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null
            ];
            
            // Add address if it exists in the data
            if (isset($data['address'])) {
                $updateData['address'] = $data['address'];
            }
            
            $updated = User::update($userId, $updateData);
            
            if ($updated) {
                // Update session data
                $_SESSION['user'] = array_merge($_SESSION['user'], $updateData);
                $_SESSION['success_message'] = 'Profile updated successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to update profile. Please try again.';
            }
            
            header('Location: /profile');
            exit;
        }
        
        header('Location: /profile');
        exit;
    }
    
    public function changePassword(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        if ($request->isPost()) {
            $data = $request->getBody();
            
            // Validate input
            $errors = $this->validate($data, [
                'current_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required'
            ]);
            
            if (!empty($errors)) {
                $_SESSION['error_message'] = 'Validation failed. Please fill all required fields.';
                header('Location: /profile');
                exit;
            }
            
            // Check if new passwords match
            if ($data['new_password'] !== $data['confirm_password']) {
                $_SESSION['error_message'] = 'New passwords do not match.';
                header('Location: /profile');
                exit;
            }
            
            // Verify current password
            $user = $this->getUserById($userId);
            if (!password_verify($data['current_password'], $user['password'])) {
                $_SESSION['error_message'] = 'Current password is incorrect.';
                header('Location: /profile');
                exit;
            }
            
            // Update password
            $updated = User::updatePassword($userId, $data['new_password']);
            
            if ($updated) {
                $_SESSION['success_message'] = 'Password updated successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to update password. Please try again.';
            }
            
            header('Location: /profile');
            exit;
        }
        
        header('Location: /profile');
        exit;
    }
    
    public function adminIndex(Request $request) {
        $page = $request->getQuery('page', 1);
        $role = $request->getQuery('role', '');
        $status = $request->getQuery('status', '');
        $search = $request->getQuery('search', '');
        $limit = 20;
        
        // Build query conditions
        $whereConditions = [];
        $params = [];
        
        if (!empty($role)) {
            $whereConditions[] = "role = ?";
            $params[] = $role;
        }
        
        if ($status === 'active') {
            $whereConditions[] = "email_verified_at IS NOT NULL";
        } elseif ($status === 'inactive') {
            $whereConditions[] = "email_verified_at IS NULL";
        }
        
        if (!empty($search)) {
            $whereConditions[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM users $whereClause";
        $countStmt = $this->query($countSql, $params);
        $totalCount = $countStmt->fetch()['total'];
        
        // Get users with pagination
        $offset = ($page - 1) * $limit;
        
        // Simplified query without complex subqueries first
        $sql = "SELECT u.* FROM users u $whereClause ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
        $usersStmt = $this->query($sql, array_merge($params, [$limit, $offset]));
        $users = $usersStmt->fetchAll();
        
        // Add statistics separately for each user
        foreach ($users as &$user) {
            $orderStats = $this->getUserOrderStats($user['id']);
            $user['orders_count'] = $orderStats['orders_count'];
            $user['total_spent'] = $orderStats['total_spent'];
        }
        $users = $usersStmt->fetchAll();
        
        $totalPages = ceil($totalCount / $limit);
        
        return $this->view('admin/users/index', [
            'title' => 'Manage Users',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount
        ]);
    }
    
    public function edit(Request $request, $id) {
        $user = $this->getUserById($id);
        
        if (!$user) {
            Session::flash('error', 'User not found');
            return $this->redirect('/admin/users');
        }
        
        return $this->view('admin/users/edit', [
            'user' => $user,
            'title' => 'Edit User'
        ]);
    }
    
    public function update(Request $request, $id) {
        if ($request->isAjax()) {
            $response = new Response(json_encode(['success' => true]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        header('Location: /admin/users');
        exit;
    }
    
    public function show(Request $request, $id) {
        $user = $this->getUserById($id);
        
        if (!$user) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'User not found']), 404);
            }
            Session::flash('error', 'User not found');
            return $this->redirect('/admin/users');
        }
        
        if ($request->isAjax()) {
            return new Response(json_encode(['success' => true, 'user' => $user]));
        }
        
        return $this->view('admin/users/show', [
            'user' => $user,
            'title' => 'User Details'
        ]);
    }
    
    public function store(Request $request) {
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required',
            'role' => 'required'
        ]);
        
        // Check if passwords match
        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'][] = 'Passwords do not match';
        }
        
        // Check if email already exists
        if ($this->getUserByEmail($data['email'])) {
            $errors['email'][] = 'Email already exists';
        }
        
        if (!empty($errors)) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'errors' => $errors]), 400);
            }
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect('/admin/users');
        }
        
        // Create user
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role'],
            'email_verified_at' => $data['email_verified'] ? date('Y-m-d H:i:s') : null
        ];
        
        $userId = $this->createUser($userData);
        
        if ($userId) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true, 'user_id' => $userId]));
            }
            Session::flash('success', 'User created successfully!');
            return $this->redirect('/admin/users');
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to create user']), 500);
            }
            Session::flash('error', 'Failed to create user. Please try again.');
            return $this->redirect('/admin/users');
        }
    }
    
    public function updateUserProfile(Request $request, $id) {
        $user = $this->getUserById($id);
        
        if (!$user) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'User not found']), 404);
            }
            Session::flash('error', 'User not found');
            return $this->redirect('/admin/users');
        }
        
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'role' => 'required'
        ]);
        
        // Check if email already exists (excluding current user)
        $existingUser = $this->getUserByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != $id) {
            $errors['email'][] = 'Email already exists';
        }
        
        // Validate passwords if provided
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors['password'][] = 'Password must be at least 8 characters';
            }
            if ($data['password'] !== $data['password_confirmation']) {
                $errors['password_confirmation'][] = 'Passwords do not match';
            }
        }
        
        if (!empty($errors)) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'errors' => $errors]), 400);
            }
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect("/admin/users/{$id}/edit");
        }
        
        // Update user data
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role']
        ];
        
        // Update password if provided
        if (!empty($data['password'])) {
            $userData['password'] = $data['password'];
        }
        
        // Update email verification
        if ($data['email_verified']) {
            $userData['email_verified_at'] = date('Y-m-d H:i:s');
        }
        
        $success = $this->updateUser($id, $userData);
        
        if ($success) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true]));
            }
            Session::flash('success', 'User updated successfully!');
            return $this->redirect('/admin/users');
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to update user']), 500);
            }
            Session::flash('error', 'Failed to update user. Please try again.');
            return $this->redirect("/admin/users/{$id}/edit");
        }
    }
    
    public function deleteUser(Request $request, $id) {
        $user = $this->getUserById($id);
        
        if (!$user) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'User not found']), 404);
            }
            Session::flash('error', 'User not found');
            return $this->redirect('/admin/users');
        }
        
        // Prevent deletion of admin users (except self)
        if ($user['role'] === 'admin' && $id != Session::get('user.id')) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Cannot delete admin users']), 400);
            }
            Session::flash('error', 'Cannot delete admin users');
            return $this->redirect('/admin/users');
        }
        
        // Delete user
        $success = $this->deleteUserHelper($id);
        
        if ($success) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true]));
            }
            Session::flash('success', 'User deleted successfully!');
            return $this->redirect('/admin/users');
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to delete user']), 500);
            }
            Session::flash('error', 'Failed to delete user. Please try again.');
            return $this->redirect('/admin/users');
        }
    }
    
    // Helper methods
    private function getUserById($id) {
        $stmt = $this->query("SELECT * FROM users WHERE id = ?", [$id]);
        return $stmt->fetch();
    }
    
    private function getUserByEmail($email) {
        $stmt = $this->query("SELECT * FROM users WHERE email = ?", [$email]);
        return $stmt->fetch();
    }
    
    private function createUser($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, address, role, email_verified_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['role'],
            $data['email_verified_at'] ?? null
        ];
        
        $stmt = $this->query($sql, $params);
        return $this->lastInsertId();
    }
    
    private function updateUser($id, $data) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, role = ?";
        $params = [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['role']
        ];
        
        if (isset($data['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['email_verified_at'])) {
            $sql .= ", email_verified_at = ?";
            $params[] = $data['email_verified_at'];
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    private function deleteUserHelper($id) {
        $stmt = $this->query("DELETE FROM users WHERE id = ?", [$id]);
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
    
    private function getUserOrderStats($userId) {
        $sql = "SELECT 
                    COUNT(*) as orders_count,
                    COALESCE(SUM(total_amount), 0) as total_spent
                FROM orders 
                WHERE user_id = ? AND status = 'completed'";
        
        $stmt = $this->query($sql, [$userId]);
        return $stmt->fetch();
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
