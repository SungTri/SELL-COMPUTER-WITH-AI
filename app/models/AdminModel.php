<?php

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getTotalRevenue($startDate = null, $endDate = null) {
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE order_status NOT IN ('cancelled', 'pending')";
        if ($startDate && $endDate) {
            $sql .= " AND ordered_at BETWEEN :start AND :end";
        }
        $this->db->query($sql);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    public function getTotalOrders($startDate = null, $endDate = null) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE order_status NOT IN ('cancelled', 'pending')";
        if ($startDate && $endDate) {
            $sql .= " AND ordered_at BETWEEN :start AND :end";
        }
        $this->db->query($sql);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    public function getTotalProducts() {
        $this->db->query("SELECT COUNT(*) as total FROM products");
        $result = $this->db->single();
        return $result['total'];
    }

    public function getTotalUsers() {
        $this->db->query("SELECT COUNT(*) as total FROM users WHERE role_id = 2");
        $result = $this->db->single();
        return $result['total'];
    }

    public function getNewUsersCount($startDate = null, $endDate = null) {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role_id = 2";
        if ($startDate && $endDate) {
            $sql .= " AND created_at BETWEEN :start AND :end";
        }
        $this->db->query($sql);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    public function getRecentOrders($limit = 5) {
        $this->db->query("
            SELECT o.id, c.full_name as customer, o.ordered_at as date, o.total_amount as total, o.order_status as status 
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            ORDER BY o.ordered_at DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getAllOrders($status = '', $search = '', $limit = 10, $offset = 0) {
        $sql = "
            SELECT o.id, c.full_name as customer, c.user_id, o.ordered_at as date, o.total_amount as total, o.order_status as status, o.payment_method 
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            WHERE 1=1
        ";
        
        if (!empty($status)) {
            $sql .= " AND o.order_status = :status";
        }
        
        if (!empty($search)) {
            $sql .= " AND (o.id LIKE :search OR c.full_name LIKE :search)";
        }
        
        $sql .= " ORDER BY o.ordered_at DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $this->db->bind(':limit', (int)$limit);
        $this->db->bind(':offset', (int)$offset);
        
        return $this->db->resultSet();
    }

    public function getOrdersCount($status = '', $search = '') {
        $sql = "
            SELECT COUNT(*) as total 
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            WHERE 1=1
        ";
        
        if (!empty($status)) {
            $sql .= " AND o.order_status = :status";
        }
        
        if (!empty($search)) {
            $sql .= " AND (o.id LIKE :search OR c.full_name LIKE :search)";
        }
        
        $this->db->query($sql);
        
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $result = $this->db->single();
        return $result['total'];
    }

    public function getOrderById($orderId) {
        $this->db->query("
            SELECT o.*, c.full_name, c.phone, c.user_id, u.email, v.code as voucher_code
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            LEFT JOIN vouchers v ON o.voucher_id = v.id
            WHERE o.id = :id
        ");
        $this->db->bind(':id', $orderId);
        return $this->db->single();
    }

    public function getOrderItems($orderId) {
        $this->db->query("
            SELECT oi.*, p.name, p.main_image as image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function updateOrderStatus($orderId, $status) {
        $this->db->query("UPDATE orders SET order_status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $orderId);
        
        if ($this->db->execute()) {
            // Log status change
            $statusMapping = [
                'pending' => 'Chờ xử lý',
                'confirmed' => 'Đã xác nhận',
                'processing' => 'Đang đóng gói',
                'shipped' => 'Đang giao hàng',
                'shipping' => 'Đang giao hàng',
                'delivered' => 'Giao hàng thành công',
                'completed' => 'Đơn hàng hoàn thành',
                'cancelled' => 'Đơn hàng đã bị hủy'
            ];
            $statusText = $statusMapping[strtolower($status)] ?? $status;
            $this->db->query("INSERT INTO order_status_logs (order_id, status, description) VALUES (:order_id, :status, :desc)");
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':status', $statusText);
            $this->db->bind(':desc', 'Trạng thái đơn hàng đã được cập nhật bởi hệ thống.');
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function updatePaymentStatus($orderId, $status) {
        $this->db->query("UPDATE orders SET payment_status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $orderId);
        
        if ($this->db->execute()) {
            // Log payment change
            $statusText = strtolower($status) == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán';
            $this->db->query("INSERT INTO order_status_logs (order_id, status, description) VALUES (:order_id, :status, :desc)");
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':status', 'Thanh toán: ' . $statusText);
            $this->db->bind(':desc', 'Hệ thống xác nhận trạng thái thanh toán của đơn hàng.');
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function getAdminProducts($search = '', $category = '', $brand = '', $limit = 10, $offset = 0) {
        $sql = "
            SELECT p.*, c.name as category_name, b.name as brand_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE 1=1
        ";
        
        if (!empty($category)) {
            $sql .= " AND (p.category_id = :category OR c.parent_id = :category)";
        }

        if (!empty($brand)) {
            $sql .= " AND p.brand_id = :brand";
        }
        
        if (!empty($search)) {
            $sql .= " AND (p.name LIKE :search OR p.id LIKE :search)";
        }
        
        $sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        
        if (!empty($category)) {
            $this->db->bind(':category', $category);
        }

        if (!empty($brand)) {
            $this->db->bind(':brand', $brand);
        }
        
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $this->db->bind(':limit', (int)$limit);
        $this->db->bind(':offset', (int)$offset);
        
        return $this->db->resultSet();
    }

    public function getAdminProductsCount($search = '', $category = '', $brand = '') {
        $sql = "SELECT COUNT(*) as total FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE 1=1";
        
        if (!empty($category)) {
            $sql .= " AND (p.category_id = :category OR c.parent_id = :category)";
        }

        if (!empty($brand)) {
            $sql .= " AND p.brand_id = :brand";
        }
        
        if (!empty($search)) {
            $sql .= " AND (p.name LIKE :search OR p.id LIKE :search)";
        }
        
        $this->db->query($sql);
        
        if (!empty($category)) {
            $this->db->bind(':category', $category);
        }

        if (!empty($brand)) {
            $this->db->bind(':brand', $brand);
        }
        
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $result = $this->db->single();
        return $result['total'];
    }

    public function getAllCategories() {
        $this->db->query("
            SELECT c.*, p.name as parent_name,
                   (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count 
            FROM categories c 
            LEFT JOIN categories p ON c.parent_id = p.id
            ORDER BY COALESCE(c.parent_id, c.id), c.parent_id IS NOT NULL, c.id
        ");
        return $this->db->resultSet();
    }

    public function getParentCategories($excludeId = null) {
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        $sql .= " ORDER BY name ASC";
        
        $this->db->query($sql);
        if ($excludeId) {
            $this->db->bind(':exclude_id', $excludeId);
        }
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query("SELECT * FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCategory($data) {
        $this->db->query("INSERT INTO categories (name, description, parent_id) VALUES (:name, :description, :parent_id)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':parent_id', !empty($data['parent_id']) ? $data['parent_id'] : null);
        return $this->db->execute();
    }

    public function updateCategory($data) {
        $this->db->query("UPDATE categories SET name = :name, description = :description, parent_id = :parent_id WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':parent_id', !empty($data['parent_id']) ? $data['parent_id'] : null);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function deleteCategory($id) {
        try {
            // First check if category has products
            $this->db->query("SELECT COUNT(*) as total FROM products WHERE category_id = :id");
            $this->db->bind(':id', $id);
            $result = $this->db->single();
            
            if ($result['total'] > 0) {
                return false; // Cannot delete category with products
            }

            $this->db->query("DELETE FROM categories WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllBrands() {
        $this->db->query("SELECT * FROM brands ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getAdminBrands() {
        $this->db->query("
            SELECT b.*, (SELECT COUNT(*) FROM products WHERE brand_id = b.id) as product_count 
            FROM brands b 
            ORDER BY b.name ASC
        ");
        return $this->db->resultSet();
    }

    public function getAdminBrandsPaginated($search = '', $limit = 10, $offset = 0) {
        if (!empty($search)) {
            $this->db->query("
                SELECT b.*, (SELECT COUNT(*) FROM products WHERE brand_id = b.id) as product_count 
                FROM brands b 
                WHERE b.name LIKE :search OR b.description LIKE :search
                ORDER BY b.name ASC
                LIMIT :limit OFFSET :offset
            ");
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query("
                SELECT b.*, (SELECT COUNT(*) FROM products WHERE brand_id = b.id) as product_count 
                FROM brands b 
                ORDER BY b.name ASC
                LIMIT :limit OFFSET :offset
            ");
        }
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getBrandsCount($search = '') {
        if (!empty($search)) {
            $this->db->query("SELECT COUNT(*) as total FROM brands WHERE name LIKE :search OR description LIKE :search");
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query("SELECT COUNT(*) as total FROM brands");
        }
        return $this->db->single()['total'];
    }

    public function getBrandById($id) {
        $this->db->query("SELECT * FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addBrand($data) {
        $this->db->query("INSERT INTO brands (name, description, logo) VALUES (:name, :description, :logo)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':logo', $data['logo']);
        return $this->db->execute();
    }

    public function updateBrand($data) {
        $sql = "UPDATE brands SET name = :name, description = :description";
        if (!empty($data['logo'])) {
            $sql .= ", logo = :logo";
        }
        $sql .= " WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':id', $data['id']);
        if (!empty($data['logo'])) {
            $this->db->bind(':logo', $data['logo']);
        }
        return $this->db->execute();
    }

    public function deleteBrand($id) {
        try {
            // Check if brand has products
            $this->db->query("SELECT COUNT(*) as total FROM products WHERE brand_id = :id");
            $this->db->bind(':id', $id);
            $result = $this->db->single();
            
            if ($result['total'] > 0) {
                return false;
            }

            $this->db->query("DELETE FROM brands WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function createProduct($data) {
        $this->db->query("
            INSERT INTO products (name, short_description, detailed_description, price, stock, category_id, brand_id, main_image, status) 
            VALUES (:name, :short_description, :detailed_description, :price, :stock, :category_id, :brand_id, :main_image, :status)
        ");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':short_description', $data['short_description']);
        $this->db->bind(':detailed_description', $data['detailed_description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':brand_id', $data['brand_id']);
        $this->db->bind(':main_image', $data['main_image']);
        $this->db->bind(':status', $data['status']);
        
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addProductImage($productId, $imagePath) {
        $this->db->query("INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)");
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':image_path', $imagePath);
        return $this->db->execute();
    }

    public function deleteProductImages($productId) {
        $this->db->query("DELETE FROM product_images WHERE product_id = :product_id");
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function getProductImageById($id) {
        $this->db->query("SELECT * FROM product_images WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function deleteProductImage($id) {
        $this->db->query("DELETE FROM product_images WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAdminProductById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateProduct($data) {
        $sql = "UPDATE products SET 
                name = :name, 
                short_description = :short_description, 
                detailed_description = :detailed_description, 
                price = :price, 
                stock = :stock, 
                category_id = :category_id, 
                brand_id = :brand_id, 
                status = :status";
        
        if (!empty($data['main_image'])) {
            $sql .= ", main_image = :main_image";
        }
        
        $sql .= " WHERE id = :id";
        
        $this->db->query($sql);
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':short_description', $data['short_description']);
        $this->db->bind(':detailed_description', $data['detailed_description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':brand_id', $data['brand_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        
        if (!empty($data['main_image'])) {
            $this->db->bind(':main_image', $data['main_image']);
        }
        
        return $this->db->execute();
    }

    public function deleteProduct($id) {
        try {
            $this->db->query("DELETE FROM products WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllVouchers() {
        $this->db->query("SELECT * FROM vouchers ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getVoucherById($id) {
        $this->db->query("SELECT * FROM vouchers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getVoucherByCode($code) {
        $this->db->query("SELECT * FROM vouchers WHERE code = :code AND status = 1");
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    public function isVoucherUsedByUser($voucherId, $userId) {
        $this->db->query("SELECT id FROM orders WHERE customer_id = :user_id AND voucher_id = :voucher_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':voucher_id', $voucherId);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function deactivateVoucher($id) {
        $this->db->query("UPDATE vouchers SET status = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function addVoucher($data) {
        $this->db->query("
            INSERT INTO vouchers (code, description, discount_percentage, discount_amount, start_date, end_date, status) 
            VALUES (:code, :description, :discount_percentage, :discount_amount, :start_date, :end_date, :status)
        ");
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':discount_percentage', $data['discount_percentage']);
        $this->db->bind(':discount_amount', $data['discount_amount']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function updateVoucher($data) {
        $this->db->query("
            UPDATE vouchers SET 
                code = :code, 
                description = :description, 
                discount_percentage = :discount_percentage, 
                discount_amount = :discount_amount, 
                start_date = :start_date, 
                end_date = :end_date, 
                status = :status 
            WHERE id = :id
        ");
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':discount_percentage', $data['discount_percentage']);
        $this->db->bind(':discount_amount', $data['discount_amount']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function deleteVoucher($id) {
        $this->db->query("DELETE FROM vouchers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllUsers($roleId = null, $search = '') {
        $sql = "
            SELECT u.*, r.name as role_name, c.full_name, c.phone 
            FROM users u
            JOIN roles r ON u.role_id = r.id
            LEFT JOIN customers c ON u.id = c.user_id
            WHERE 1=1
        ";
        
        if ($roleId) {
            $sql .= " AND u.role_id = :role_id";
        }
        
        if (!empty($search)) {
            $sql .= " AND (u.email LIKE :search OR c.full_name LIKE :search OR c.phone LIKE :search)";
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $this->db->query($sql);
        
        if ($roleId) $this->db->bind(':role_id', $roleId);
        if (!empty($search)) $this->db->bind(':search', '%' . $search . '%');
        
        return $this->db->resultSet();
    }

    public function getAdminUserById($id) {
        $this->db->query("
            SELECT u.*, r.name as role_name, c.full_name, c.phone, c.address, c.gender 
            FROM users u
            JOIN roles r ON u.role_id = r.id
            LEFT JOIN customers c ON u.id = c.user_id
            WHERE u.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUserOrders($userId) {
        $this->db->query("
            SELECT o.* 
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            WHERE c.user_id = :user_id
            ORDER BY o.ordered_at DESC
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getUserReviews($userId) {
        $this->db->query("
            SELECT r.*, p.name as product_name, p.main_image as product_image
            FROM reviews r
            JOIN customers c ON r.customer_id = c.id
            JOIN products p ON r.product_id = p.id
            WHERE c.user_id = :user_id
            ORDER BY r.created_at DESC
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getUserTotalSpent($userId) {
        $this->db->query("
            SELECT SUM(o.total_amount) as total 
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            WHERE c.user_id = :user_id AND o.order_status NOT IN ('cancelled', 'pending')
        ");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }


    public function updateUserStatus($id, $status) {
        $this->db->query("UPDATE users SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteUserPermanently($userId) {
        // 1. Lấy email và ID khách hàng
        $this->db->query("SELECT email FROM users WHERE id = :id");
        $this->db->bind(':id', $userId);
        $user = $this->db->single();
        if (!$user) {
            return false;
        }
        $email = $user['email'];

        $this->db->query("SELECT id FROM customers WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $customer = $this->db->single();
        $customerId = $customer ? $customer['id'] : null;

        if ($customerId) {
            // Xóa logs trạng thái đơn hàng
            $this->db->query("DELETE FROM order_status_logs WHERE order_id IN (SELECT id FROM orders WHERE customer_id = :customer_id)");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa các sản phẩm trong đơn hàng
            $this->db->query("DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE customer_id = :customer_id)");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa đơn hàng
            $this->db->query("DELETE FROM orders WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa sản phẩm yêu thích (wishlist)
            $this->db->query("DELETE FROM wishlist WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa sản phẩm trong giỏ hàng
            $this->db->query("DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM carts WHERE customer_id = :customer_id)");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa giỏ hàng
            $this->db->query("DELETE FROM carts WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa địa chỉ khách hàng
            $this->db->query("DELETE FROM addresses WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa đánh giá sản phẩm
            $this->db->query("DELETE FROM reviews WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa lịch sử trò chuyện AI
            $this->db->query("DELETE FROM chat_history WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa phản hồi
            $this->db->query("DELETE FROM feedback WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();

            // Xóa hồ sơ khách hàng
            $this->db->query("DELETE FROM customers WHERE id = :customer_id");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();
        }

        // Xóa thông báo của người dùng
        $this->db->query("DELETE FROM user_notifications WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $this->db->execute();

        // Xóa voucher của người dùng
        $this->db->query("DELETE FROM user_vouchers WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $this->db->execute();

        // Xóa yêu cầu đặt lại mật khẩu
        $this->db->query("DELETE FROM password_resets WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();

        // Xóa tài khoản người dùng
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    public function getAllRoles() {
        $this->db->query("SELECT * FROM roles");
        return $this->db->resultSet();
    }

    public function getRevenueByMonth() {
        $this->db->query("
            SELECT 
                DATE_FORMAT(ordered_at, '%m/%Y') as month,
                SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending')
            GROUP BY DATE_FORMAT(ordered_at, '%m/%Y')
            ORDER BY ordered_at DESC
            LIMIT 6
        ");
        $results = $this->db->resultSet();
        
        // Tự động tạo mảng 6 tháng gần nhất theo thứ tự thời gian tăng dần
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('m/Y', strtotime("-$i months"));
            $months[$m] = 0.0;
        }
        
        foreach ($results as $row) {
            $month = $row['month'];
            if (isset($months[$month])) {
                $months[$month] = (float)$row['revenue'];
            }
        }
        
        $chartData = [];
        foreach ($months as $m => $rev) {
            $chartData[] = [
                'month' => $m,
                'revenue' => $rev
            ];
        }
        return $chartData;
    }

    public function getRevenueByDateRange($startDate, $endDate) {
        $diff = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
        $format = $diff <= 60 ? '%d/%m' : '%m/%Y';
        
        $this->db->query("
            SELECT 
                DATE_FORMAT(ordered_at, :format) as label,
                SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending') 
            AND ordered_at BETWEEN :start AND :end
            GROUP BY label
            ORDER BY ordered_at ASC
        ");
        $this->db->bind(':format', $format);
        $this->db->bind(':start', $startDate . ' 00:00:00');
        $this->db->bind(':end', $endDate . ' 23:59:59');
        $results = $this->db->resultSet();
        
        // Tự động sinh danh sách đầy đủ tất cả các ngày hoặc tháng trong khoảng tìm kiếm
        $allLabels = [];
        if ($diff <= 60) {
            $current = strtotime($startDate);
            $last = strtotime($endDate);
            while ($current <= $last) {
                $allLabels[date('d/m', $current)] = 0.0;
                $current = strtotime('+1 day', $current);
            }
        } else {
            $current = strtotime(date('Y-m-01', strtotime($startDate)));
            $last = strtotime(date('Y-m-01', strtotime($endDate)));
            while ($current <= $last) {
                $allLabels[date('m/Y', $current)] = 0.0;
                $current = strtotime('+1 month', $current);
            }
        }
        
        foreach ($results as $row) {
            $label = $row['label'];
            if (isset($allLabels[$label])) {
                $allLabels[$label] = (float)$row['revenue'];
            }
        }
        
        $chartData = [];
        foreach ($allLabels as $lbl => $rev) {
            $chartData[] = [
                'label' => $lbl,
                'revenue' => $rev
            ];
        }
        return $chartData;
    }

    public function getOrderStatusDistribution($startDate = null, $endDate = null) {
        $sql = "
            SELECT order_status as status, COUNT(*) as count 
            FROM orders";
        if ($startDate && $endDate) {
            $sql .= " WHERE ordered_at BETWEEN :start AND :end";
        }
        $sql .= " GROUP BY order_status";
        
        $this->db->query($sql);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        return $this->db->resultSet();
    }

    public function getTopSellingProducts($limit = 5, $startDate = null, $endDate = null) {
        $sql = "
            SELECT p.id, p.name, SUM(oi.quantity) as sold_count, p.price, p.main_image as image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE LOWER(o.order_status) IN ('delivered', 'completed', 'shipped')";
        if ($startDate && $endDate) {
            $sql .= " AND o.ordered_at BETWEEN :start AND :end";
        }
        $sql .= "
            GROUP BY p.id
            ORDER BY sold_count DESC
            LIMIT :limit
        ";
        $this->db->query($sql);
        $this->db->bind(':limit', $limit);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        return $this->db->resultSet();
    }

    public function getVoucherSubscriberEmails() {
        // Chỉ lấy những tài khoản khách hàng (role_id = 2) và đang hoạt động (status = 1)
        $this->db->query("SELECT id, email FROM users WHERE status = 1 AND role_id != 1");
        return $this->db->resultSet();
    }

    public function createNotification($userId, $title, $content, $type = 'promotion') {
        $this->db->query("
            INSERT INTO user_notifications (user_id, title, content, type) 
            VALUES (:user_id, :title, :content, :type)
        ");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', $title);
        $this->db->bind(':content', $content);
        $this->db->bind(':type', $type);
        return $this->db->execute();
    }

    public function getAllReviews($search = '') {
        $sql = "
            SELECT r.*, c.full_name as customer_name, p.name as product_name, p.main_image as product_image
            FROM reviews r
            JOIN customers c ON r.customer_id = c.id
            JOIN products p ON r.product_id = p.id
            WHERE 1=1
        ";
        
        if (!empty($search)) {
            $sql .= " AND (c.full_name LIKE :search OR p.name LIKE :search OR r.comment LIKE :search)";
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        $this->db->query($sql);
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        return $this->db->resultSet();
    }

    public function deleteReview($id) {
        $this->db->query("DELETE FROM reviews WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function replyReview($id, $reply) {
        $this->db->query("UPDATE reviews SET admin_reply = :reply, replied_at = NOW() WHERE id = :id");
        $this->db->bind(':reply', $reply);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllFeedbacks($search = '') {
        $sql = "
            SELECT f.*, c.full_name as customer_name, u.email as customer_email
            FROM feedback f
            JOIN customers c ON f.customer_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE 1=1
        ";
        
        if (!empty($search)) {
            $sql .= " AND (c.full_name LIKE :search OR u.email LIKE :search OR f.title LIKE :search OR f.content LIKE :search)";
        }
        
        $sql .= " ORDER BY f.submitted_at DESC";
        
        $this->db->query($sql);
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        return $this->db->resultSet();
    }

    public function deleteFeedback($id) {
        $this->db->query("DELETE FROM feedback WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateFeedbackStatus($id, $status) {
        $this->db->query("UPDATE feedback SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSettings() {
        $this->db->query("SELECT * FROM settings");
        return $this->db->resultSet();
    }

    public function updateSetting($key, $value) {
        $this->db->query("UPDATE settings SET value = :value WHERE `key` = :key");
        $this->db->bind(':value', $value);
        $this->db->bind(':key', $key);
        return $this->db->execute();
    }

    public function getTodayRevenue() {
        $this->db->query("
            SELECT SUM(total_amount) as total 
            FROM orders 
            WHERE order_status NOT IN ('cancelled', 'pending') 
            AND DATE(ordered_at) = CURRENT_DATE()
        ");
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    public function getRevenueByCategory($startDate = null, $endDate = null) {
        $sql = "
            SELECT c.name as label, COALESCE(sum_val.revenue, 0) as value
            FROM categories c
            LEFT JOIN (
                SELECT p.category_id, SUM(oi.quantity * oi.price_at_purchase) as revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.order_status NOT IN ('cancelled', 'pending')";
        if ($startDate && $endDate) {
            $sql .= " AND o.ordered_at BETWEEN :start AND :end";
        }
        $sql .= "
                GROUP BY p.category_id
            ) sum_val ON c.id = sum_val.category_id
            ORDER BY value DESC
        ";
        $this->db->query($sql);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        return $this->db->resultSet();
    }


    public function getGrowthStats() {
        // This Month
        $this->db->query("
            SELECT COUNT(*) as orders, SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending') 
            AND MONTH(ordered_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(ordered_at) = YEAR(CURRENT_DATE())
        ");
        $thisMonth = $this->db->single();

        // Last Month
        $this->db->query("
            SELECT COUNT(*) as orders, SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending') 
            AND MONTH(ordered_at) = MONTH(STR_TO_DATE(DATE_FORMAT(NOW() ,'%Y-%m-01'),'%Y-%m-%d') - INTERVAL 1 MONTH)
            AND YEAR(ordered_at) = YEAR(STR_TO_DATE(DATE_FORMAT(NOW() ,'%Y-%m-01'),'%Y-%m-%d') - INTERVAL 1 MONTH)
        ");
        $lastMonth = $this->db->single();

        // New Users This Month
        $this->db->query("SELECT COUNT(*) as total FROM users WHERE role_id = 2 AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $newUsers = $this->db->single();

        // New Users Last Month
        $this->db->query("
            SELECT COUNT(*) as total FROM users 
            WHERE role_id = 2 
            AND MONTH(created_at) = MONTH(STR_TO_DATE(DATE_FORMAT(NOW() ,'%Y-%m-01'),'%Y-%m-%d') - INTERVAL 1 MONTH)
            AND YEAR(created_at) = YEAR(STR_TO_DATE(DATE_FORMAT(NOW() ,'%Y-%m-01'),'%Y-%m-%d') - INTERVAL 1 MONTH)
        ");
        $lastMonthUsers = $this->db->single();

        return [
            'this_month' => [
                'revenue' => $thisMonth['revenue'] ?? 0,
                'orders' => $thisMonth['orders'] ?? 0,
                'users' => $newUsers['total'] ?? 0
            ],
            'last_month' => [
                'revenue' => $lastMonth['revenue'] ?? 0,
                'orders' => $lastMonth['orders'] ?? 0,
                'users' => $lastMonthUsers['total'] ?? 0
            ]
        ];
    }

    public function getTopCustomers($limit = 5, $startDate = null, $endDate = null) {
        $sql = "
            SELECT c.full_name as name, u.email, SUM(o.total_amount) as total_spent, COUNT(o.id) as order_count
            FROM customers c
            JOIN users u ON c.user_id = u.id
            JOIN orders o ON c.id = o.customer_id
            WHERE o.order_status NOT IN ('cancelled', 'pending')";
        if ($startDate && $endDate) {
            $sql .= " AND o.ordered_at BETWEEN :start AND :end";
        }
        $sql .= "
            GROUP BY c.id
            ORDER BY total_spent DESC
            LIMIT :limit
        ";
        $this->db->query($sql);
        $this->db->bind(':limit', $limit);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        return $this->db->resultSet();
    }

    public function getDailyRevenue($days = 30) {
        $this->db->query("
            SELECT DATE(ordered_at) as date, SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending') AND ordered_at >= DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY)
            GROUP BY DATE(ordered_at)
            ORDER BY date ASC
        ");
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    public function getLowStockProducts($limit = 5) {
        $this->db->query("
            SELECT p.id, p.name, p.stock, p.price, p.main_image as image
            FROM products p
            WHERE p.stock <= 5 AND p.status = 1
            ORDER BY p.stock ASC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getRevenueByBrand($startDate = null, $endDate = null) {
        $sql = "
            SELECT b.name as label, SUM(oi.quantity * oi.price_at_purchase) as value
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN brands b ON p.brand_id = b.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.order_status NOT IN ('cancelled', 'pending')";
        if ($startDate && $endDate) {
            $sql .= " AND o.ordered_at BETWEEN :start AND :end";
        }
        $sql .= "
            GROUP BY b.id
            ORDER BY value DESC
            LIMIT 7
        ";
        $this->db->query($sql);
        if ($startDate && $endDate) {
            $this->db->bind(':start', $startDate . ' 00:00:00');
            $this->db->bind(':end', $endDate . ' 23:59:59');
        }
        return $this->db->resultSet();
    }
    public function getRevenueByWeek($weeks = 8) {
        $this->db->query("
            SELECT 
                DATE_FORMAT(ordered_at, '%v/%x') as week_label,
                DATE_SUB(DATE(ordered_at), INTERVAL WEEKDAY(ordered_at) DAY) as week_start,
                SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending') 
            AND ordered_at >= DATE_SUB(CURRENT_DATE(), INTERVAL :weeks WEEK)
            GROUP BY week_label
            ORDER BY week_start ASC
        ");
        $this->db->bind(':weeks', $weeks);
        $results = $this->db->resultSet();
        
        foreach ($results as &$row) {
            $row['label'] = 'Tuần ' . explode('/', $row['week_label'])[0];
        }
        return $results;
    }

    public function getBankTransactions($limit = 50) {
        $this->db->query("SELECT * FROM bank_transactions ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function saveUserVoucher($userId, $voucherId) {
        // Check if already exists
        $this->db->query("SELECT id FROM user_vouchers WHERE user_id = :user_id AND voucher_id = :voucher_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':voucher_id', $voucherId);
        
        if ($this->db->single()) {
            return true; // Already exists
        }

        $this->db->query("INSERT INTO user_vouchers (user_id, voucher_id, status) VALUES (:user_id, :voucher_id, 1)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':voucher_id', $voucherId);
        return $this->db->execute();
    }

    public function getAllUsedImages() {
        $used = [];

        // 1. Products main images
        $this->db->query("SELECT id, name, main_image FROM products WHERE main_image IS NOT NULL AND main_image != ''");
        $products = $this->db->resultSet();
        foreach ($products as $p) {
            $url = $p['main_image'];
            $used[$url][] = [
                'type' => 'product_main',
                'id' => $p['id'],
                'name' => $p['name'],
                'detail' => 'Ảnh chính sản phẩm #' . $p['id'] . ' (' . $p['name'] . ')'
            ];
        }

        // 2. Product secondary images
        $this->db->query("SELECT pi.product_id, p.name, pi.image_path FROM product_images pi JOIN products p ON pi.product_id = p.id WHERE pi.image_path IS NOT NULL AND pi.image_path != ''");
        $secImages = $this->db->resultSet();
        foreach ($secImages as $img) {
            $url = $img['image_path'];
            $used[$url][] = [
                'type' => 'product_secondary',
                'id' => $img['product_id'],
                'name' => $img['name'],
                'detail' => 'Ảnh phụ sản phẩm #' . $img['product_id'] . ' (' . $img['name'] . ')'
            ];
        }

        // 3. Brand logos
        $this->db->query("SELECT id, name, logo FROM brands WHERE logo IS NOT NULL AND logo != ''");
        $brands = $this->db->resultSet();
        foreach ($brands as $b) {
            $url = $b['logo'];
            $used[$url][] = [
                'type' => 'brand_logo',
                'id' => $b['id'],
                'name' => $b['name'],
                'detail' => 'Logo thương hiệu #' . $b['id'] . ' (' . $b['name'] . ')'
            ];
        }

        // 4. Review images
        $this->db->query("SELECT r.id, p.name as product_name, r.review_image FROM reviews r JOIN products p ON r.product_id = p.id WHERE r.review_image IS NOT NULL AND r.review_image != ''");
        $reviews = $this->db->resultSet();
        foreach ($reviews as $rev) {
            $url = $rev['review_image'];
            $used[$url][] = [
                'type' => 'review_image',
                'id' => $rev['id'],
                'name' => $rev['product_name'],
                'detail' => 'Ảnh đánh giá sản phẩm (' . $rev['product_name'] . ')'
            ];
        }

        return $used;
    }

    public function getRevenueByYear() {
        $this->db->query("
            SELECT 
                DATE_FORMAT(ordered_at, '%Y') as year,
                SUM(total_amount) as revenue
            FROM orders
            WHERE order_status NOT IN ('cancelled', 'pending')
            GROUP BY YEAR(ordered_at)
            ORDER BY year DESC
        ");
        return $this->db->resultSet();
    }

    public function updateUserRole($userId, $roleId) {
        $this->db->query("UPDATE users SET role_id = :role_id WHERE id = :id");
        $this->db->bind(':role_id', $roleId);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    public function getProductVariants($productId) {
        $this->db->query("SELECT * FROM product_variants WHERE product_id = :product_id ORDER BY id ASC");
        $this->db->bind(':product_id', $productId);
        $variants = $this->db->resultSet();
        
        foreach ($variants as &$v) {
            $v['options'] = $this->getVariantOptions($v['id']);
        }
        return $variants;
    }

    public function getVariantOptions($variantId) {
        $this->db->query("SELECT attribute_name, attribute_value FROM product_variant_options WHERE variant_id = :variant_id");
        $this->db->bind(':variant_id', $variantId);
        return $this->db->resultSet();
    }

    public function saveProductVariants($productId, $variants) {
        $this->db->query("SELECT id FROM product_variants WHERE product_id = :product_id");
        $this->db->bind(':product_id', $productId);
        $oldVariants = $this->db->resultSet();
        $oldIds = array_column($oldVariants, 'id');
        
        if (!empty($oldIds)) {
            $this->db->query("DELETE FROM product_variants WHERE product_id = :product_id");
            $this->db->bind(':product_id', $productId);
            $this->db->execute();
        }
        
        if (empty($variants) || !is_array($variants)) {
            return true;
        }
        
        foreach ($variants as $v) {
            $price = floatval($v['price'] ?? 0);
            $stock = intval($v['stock'] ?? 0);
            $sku = trim($v['sku'] ?? '');
            $image = trim($v['image'] ?? '');
            
            $this->db->query("INSERT INTO product_variants (product_id, sku, price, stock, image) VALUES (:product_id, :sku, :price, :stock, :image)");
            $this->db->bind(':product_id', $productId);
            $this->db->bind(':sku', $sku ?: null);
            $this->db->bind(':price', $price);
            $this->db->bind(':stock', $stock);
            $this->db->bind(':image', $image ?: null);
            
            if ($this->db->execute()) {
                $variantId = $this->db->lastInsertId();
                
                if (!empty($v['options']) && is_array($v['options'])) {
                    foreach ($v['options'] as $attrName => $attrVal) {
                        if (trim($attrName) !== '' && trim($attrVal) !== '') {
                            $this->db->query("INSERT INTO product_variant_options (variant_id, product_id, attribute_name, attribute_value) VALUES (:variant_id, :product_id, :attribute_name, :attribute_value)");
                            $this->db->bind(':variant_id', $variantId);
                            $this->db->bind(':product_id', $productId);
                            $this->db->bind(':attribute_name', trim($attrName));
                            $this->db->bind(':attribute_value', trim($attrVal));
                            $this->db->execute();
                        }
                    }
                }
            }
        }
        return true;
    }
}

