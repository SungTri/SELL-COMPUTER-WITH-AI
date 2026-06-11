<?php

class BuildpcController extends Controller {
    public function index() {
        $db = new Database();
        $categoryModel = $this->model('CategoryModel');
        $categories = $categoryModel->getCategoriesWithBrands();

        // Get app settings for header
        $db->query("SELECT * FROM settings");
        $settings = $db->resultSet();
        $appSettings = [];
        foreach ($settings as $s) {
            $appSettings[$s['key']] = $s['value'];
        }

        $data = [
            'title' => 'Xây dựng cấu hình PC Chuyên Nghiệp | TechExpert',
            'meta_description' => 'Công cụ tự xây dựng cấu hình máy tính (PC Build) tối ưu, tương thích hoàn hảo. Hỗ trợ lắp ráp máy tính chơi game, đồ họa, văn phòng với giá tốt nhất.',
            'meta_keywords' => 'build pc, tu dung cau hinh pc, lap rap may tinh, pc gaming, pc do hoa, tuong thich linh kien',
            'categories' => $categories,
            'app_settings' => $appSettings
        ];
        
        $this->view('pages/buildpc', $data);
    }

    public function getProductsByCategory($categoryId = null) {
        ob_start();
        if (!$categoryId) {
            $categoryId = $_GET['category_id'] ?? null;
        }

        if (!$categoryId) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Missing category_id']);
            exit();
        }

        $search = trim($_GET['search'] ?? '');
        $brandId = $_GET['brand_id'] ?? null;

        $db = new Database();
        
        // Construct SQL query
        $sql = "SELECT p.*, b.name as brand_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.category_id = :category_id AND p.status = 1";
                
        if (!empty($search)) {
            $sql .= " AND (p.name LIKE :search OR p.short_description LIKE :search)";
        }
        if ($brandId) {
            $sql .= " AND p.brand_id = :brand_id";
        }
        
        $sql .= " ORDER BY p.id DESC LIMIT 100";

        $db->query($sql);
        $db->bind(':category_id', $categoryId);
        if (!empty($search)) {
            $db->bind(':search', '%' . $search . '%');
        }
        if ($brandId) {
            $db->bind(':brand_id', $brandId);
        }

        $products = $db->resultSet();

        // Format products to make links absolute and format prices
        $formatted = [];
        foreach ($products as $p) {
            $img = get_product_image($p['main_image']);

            $formatted[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => (float)$p['price'],
                'price_formatted' => number_format($p['price'], 0, ',', '.') . ' VNĐ',
                'image' => $img,
                'short_description' => $p['short_description'] ?? '',
                'stock' => (int)$p['stock'],
                'brand_name' => $p['brand_name'] ?? 'Khác'
            ];
        }

        // Get brands in this category for filtering
        $db->query("SELECT DISTINCT b.id, b.name 
                    FROM brands b 
                    JOIN products p ON p.brand_id = b.id 
                    WHERE p.category_id = :category_id AND p.status = 1 
                    ORDER BY b.name ASC");
        $db->bind(':category_id', $categoryId);
        $brands = $db->resultSet();

        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'products' => $formatted,
            'brands' => $brands
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
