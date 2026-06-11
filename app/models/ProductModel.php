<?php

class ProductModel {
    private $db;
    private $stmt;

    public function __construct() {
        $this->db = new Database();
    }

    public function getFeaturedProducts($limit = 8) {
        $sql = "SELECT p.*, b.name as brand_name, 
                       (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.status = 1 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getAllProducts() {
        $this->db->query("SELECT id, name, created_at FROM products WHERE status = 1 ORDER BY created_at DESC");
        return $this->db->resultSet();
    }



    public function getProductsByCategory($categoryId, $filters = [], $limit = 12, $offset = 0) {
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.category_id = :category_id OR c.parent_id = :category_id) AND p.status = 1";
        
        if (!empty($filters['brand'])) {
            $brands = is_array($filters['brand']) ? $filters['brand'] : [$filters['brand']];
            $placeholders = [];
            foreach ($brands as $index => $brandId) {
                $placeholders[] = ":brand_id_{$index}";
            }
            $sql .= " AND p.brand_id IN (" . implode(',', $placeholders) . ")";
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= :price_min";
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= :price_max";
        }
        
        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.created_at DESC";
                break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $this->db->query($sql);
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        if (!empty($filters['brand'])) {
            $brands = is_array($filters['brand']) ? $filters['brand'] : [$filters['brand']];
            foreach ($brands as $index => $brandId) {
                $this->db->bind(":brand_id_{$index}", $brandId);
            }
        }
        if (!empty($filters['price_min'])) {
            $this->db->bind(':price_min', $filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $this->db->bind(':price_max', $filters['price_max']);
        }

        return $this->db->resultSet();
    }

    public function getTotalProductsByCategory($categoryId, $filters = []) {
        $sql = "SELECT COUNT(*) as total 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.category_id = :category_id OR c.parent_id = :category_id) AND p.status = 1";
        
        if (!empty($filters['brand'])) {
            $brands = is_array($filters['brand']) ? $filters['brand'] : [$filters['brand']];
            $placeholders = [];
            foreach ($brands as $index => $brandId) {
                $placeholders[] = ":brand_id_{$index}";
            }
            $sql .= " AND p.brand_id IN (" . implode(',', $placeholders) . ")";
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= :price_min";
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= :price_max";
        }

        $this->db->query($sql);
        $this->db->bind(':category_id', $categoryId);
        
        if (!empty($filters['brand'])) {
            $brands = is_array($filters['brand']) ? $filters['brand'] : [$filters['brand']];
            foreach ($brands as $index => $brandId) {
                $this->db->bind(":brand_id_{$index}", $brandId);
            }
        }
        if (!empty($filters['price_min'])) {
            $this->db->bind(':price_min', $filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $this->db->bind(':price_max', $filters['price_max']);
        }

        return $this->db->single()['total'];
    }

    public function getBrandsByCategory($categoryId) {
        $sql = "SELECT DISTINCT b.* 
                FROM brands b 
                JOIN products p ON b.id = p.brand_id 
                JOIN categories c ON p.category_id = c.id
                WHERE (p.category_id = :category_id OR c.parent_id = :category_id)
                AND p.status = 1
                ORDER BY b.name ASC";
        $this->db->query($sql);
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    public function searchProducts($keyword, $filters = []) {
        $keyword = trim($keyword);
        $words = array_filter(explode(' ', $keyword));
        
        $wordConditions = [];
        foreach ($words as $index => $word) {
            $wordConditions[] = "(p.name LIKE :word_{$index} OR p.short_description LIKE :word_{$index} OR b.name LIKE :word_{$index})";
        }
        
        $whereClause = !empty($wordConditions) ? implode(' AND ', $wordConditions) : "1=1";

        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE {$whereClause}
                AND p.status = 1";
        
        if (!empty($filters['brand'])) {
            $brands = is_array($filters['brand']) ? $filters['brand'] : [$filters['brand']];
            $placeholders = [];
            foreach ($brands as $index => $brandId) {
                $placeholders[] = ":brand_id_{$index}";
            }
            $sql .= " AND p.brand_id IN (" . implode(',', $placeholders) . ")";
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= :price_min";
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= :price_max";
        }

        // Sorting
        switch ($filters['sort'] ?? 'newest') {
            case 'price_asc': $sql .= " ORDER BY p.price ASC"; break;
            case 'price_desc': $sql .= " ORDER BY p.price DESC"; break;
            case 'popular': $sql .= " ORDER BY p.view_count DESC"; break;
            default: $sql .= " ORDER BY p.created_at DESC"; break;
        }

        $this->db->query($sql);
        
        foreach ($words as $index => $word) {
            $this->db->bind(":word_{$index}", "%{$word}%");
        }
        
        if (!empty($filters['brand'])) {
            foreach ($brands as $index => $brandId) {
                $this->db->bind(":brand_id_{$index}", $brandId);
            }
        }
        if (!empty($filters['price_min'])) {
            $this->db->bind(':price_min', $filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $this->db->bind(':price_max', $filters['price_max']);
        }

        return $this->db->resultSet();
    }

    public function getBrandsBySearch($keyword) {
        $keyword = trim($keyword);
        $words = array_filter(explode(' ', $keyword));
        
        $wordConditions = [];
        foreach ($words as $index => $word) {
            $wordConditions[] = "(p.name LIKE :word_{$index} OR p.short_description LIKE :word_{$index} OR b.name LIKE :word_{$index})";
        }
        
        $whereClause = !empty($wordConditions) ? implode(' AND ', $wordConditions) : "1=1";

        $sql = "SELECT DISTINCT b.* 
                FROM brands b 
                JOIN products p ON b.id = p.brand_id 
                WHERE {$whereClause}
                AND p.status = 1
                ORDER BY b.name ASC";
        $this->db->query($sql);
        
        foreach ($words as $index => $word) {
            $this->db->bind(":word_{$index}", "%{$word}%");
        }
        
        return $this->db->resultSet();
    }

    public function incrementViewCount($id) {
        $this->db->query("UPDATE products SET view_count = view_count + 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getProductById($id) {
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name, c.id as category_id,
                       (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating,
                       (SELECT COUNT(*) FROM reviews WHERE product_id = p.id) as review_count
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id AND p.status = 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getProductImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = :product_id";
        $this->db->query($sql);
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    public function getReviewsByProductId($id) {
        $sql = "SELECT r.*, c.full_name as customer_name 
                FROM reviews r
                JOIN customers c ON r.customer_id = c.id
                WHERE r.product_id = :product_id
                ORDER BY r.created_at DESC";
        $this->db->query($sql);
        $this->db->bind(':product_id', $id);
        return $this->db->resultSet();
    }

    /**
     * Lấy các đánh giá nổi bật cho trang chủ (rating cao, có nội dung)
     *
     * @param int $limit
     * @return array
     */
    public function getTopReviews($limit = 3) {
        $sql = "SELECT r.rating, r.comment, r.created_at,
                       c.full_name as customer_name,
                       p.name as product_name,
                       p.id as product_id
                FROM reviews r
                JOIN customers c ON r.customer_id = c.id
                JOIN products p ON r.product_id = p.id
                WHERE r.rating >= 4 AND r.comment IS NOT NULL AND TRIM(r.comment) != ''
                ORDER BY r.rating DESC, CHAR_LENGTH(r.comment) DESC
                LIMIT :limit";
        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $sql = "SELECT p.*, b.name as brand_name
                FROM products p
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.category_id = :category_id AND p.id != :product_id AND p.status = 1
                LIMIT :limit";
        $this->db->query($sql);
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function addReview($data) {
        $sql = "INSERT INTO reviews (customer_id, product_id, rating, comment, review_image) 
                VALUES (:customer_id, :product_id, :rating, :comment, :review_image)";
        $this->db->query($sql);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        $this->db->bind(':review_image', $data['review_image'] ?? null);
        return $this->db->execute();
    }

    public function hasBoughtProduct($customerId, $productId) {
        $sql = "SELECT COUNT(*) as total 
                FROM orders o 
                JOIN order_items oi ON o.id = oi.order_id 
                WHERE o.customer_id = :customer_id 
                AND oi.product_id = :product_id 
                AND LOWER(o.order_status) IN ('delivered', 'completed', 'shipped')";
        
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        $result = $this->db->single();
        
        return $result['total'] > 0;
    }

    public function getBestSellers($limit = 4) {
        $sql = "SELECT p.*, b.name as brand_name, SUM(oi.quantity) as sold_count,
                       (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
                FROM products p
                JOIN order_items oi ON p.id = oi.product_id
                JOIN orders o ON oi.order_id = o.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.status = 1 AND LOWER(o.order_status) IN ('delivered', 'completed', 'shipped')
                GROUP BY p.id
                ORDER BY sold_count DESC
                LIMIT :limit";
        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getNewArrivals($limit = 4) {
        $sql = "SELECT p.*, b.name as brand_name,
                       (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
                FROM products p
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.status = 1
                ORDER BY p.created_at DESC
                LIMIT :limit";
        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
    public function getSubcategories($parentId) {
        $this->db->query("SELECT * FROM categories WHERE parent_id = :parent_id ORDER BY name ASC");
        $this->db->bind(':parent_id', $parentId);
        return $this->db->resultSet();
    }

    public function getAllBrands() {
        $this->db->query("SELECT * FROM brands ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getOnSaleProducts($limit = 4) {
        // Since we don't have a specific discount column, let's just pick some featured ones 
        // or assume those with specific IDs are on sale for demo purposes.
        // Or better, just random featured for now.
        return $this->getFeaturedProducts($limit);
    }

    public function decreaseStock($productId, $quantity) {
        $sql = "UPDATE products SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity";
        $this->db->query($sql);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        $this->db->bind(':id', $productId, PDO::PARAM_INT);
        return $this->db->execute() && $this->db->rowCount() > 0;
    }

    public function increaseStock($productId, $quantity) {
        $sql = "UPDATE products SET stock = stock + :quantity WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        $this->db->bind(':id', $productId, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function checkStock($productId, $requestedQuantity, $variantId = null) {
        if ($variantId) {
            $this->db->query("
                SELECT pv.stock, p.name, 
                       GROUP_CONCAT(CONCAT(pvo.attribute_name, ': ', pvo.attribute_value) SEPARATOR ', ') as variant_name 
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                LEFT JOIN product_variant_options pvo ON pv.id = pvo.variant_id
                WHERE pv.id = :variant_id AND pv.product_id = :product_id
                GROUP BY pv.id
            ");
            $this->db->bind(':variant_id', $variantId);
            $this->db->bind(':product_id', $productId);
            $variant = $this->db->single();
            
            if (!$variant) {
                return ['status' => 'error', 'message' => 'Phiên bản sản phẩm không tồn tại'];
            }
            
            $fullName = $variant['name'] . ' (' . $variant['variant_name'] . ')';
            if ($variant['stock'] < $requestedQuantity) {
                return [
                    'status' => 'insufficient',
                    'name' => $fullName,
                    'available' => $variant['stock']
                ];
            }
        } else {
            $this->db->query("SELECT name, stock FROM products WHERE id = :id");
            $this->db->bind(':id', $productId);
            $product = $this->db->single();
            
            if (!$product) {
                return ['status' => 'error', 'message' => 'Sản phẩm không tồn tại'];
            }
            
            if ($product['stock'] < $requestedQuantity) {
                return [
                    'status' => 'insufficient',
                    'name' => $product['name'],
                    'available' => $product['stock']
                ];
            }
        }
        
        return ['status' => 'ok'];
    }

    public function decreaseVariantStock($variantId, $quantity) {
        $sql = "UPDATE product_variants SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity";
        $this->db->query($sql);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        $this->db->bind(':id', $variantId, PDO::PARAM_INT);
        return $this->db->execute() && $this->db->rowCount() > 0;
    }

    public function increaseVariantStock($variantId, $quantity) {
        $sql = "UPDATE product_variants SET stock = stock + :quantity WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        $this->db->bind(':id', $variantId, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function getProductVariants($productId) {
        $this->db->query("SELECT * FROM product_variants WHERE product_id = :product_id ORDER BY id ASC");
        $this->db->bind(':product_id', $productId);
        $variants = $this->db->resultSet();
        foreach ($variants as &$v) {
            $this->db->query("SELECT attribute_name, attribute_value FROM product_variant_options WHERE variant_id = :variant_id");
            $this->db->bind(':variant_id', $v['id']);
            $v['options'] = $this->db->resultSet();
        }
        unset($v);
        return $variants;
    }

    public function getVariantById($variantId) {
        $this->db->query("
            SELECT pv.*, 
                   GROUP_CONCAT(CONCAT(pvo.attribute_name, ': ', pvo.attribute_value) SEPARATOR ', ') as variant_name
            FROM product_variants pv
            LEFT JOIN product_variant_options pvo ON pv.id = pvo.variant_id
            WHERE pv.id = :id
            GROUP BY pv.id
        ");
        $this->db->bind(':id', $variantId);
        return $this->db->single();
    }



    public function getProductsByIds($ids) {
        if (empty($ids)) return [];
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name,
                       (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating
                FROM products p 
                JOIN brands b ON p.brand_id = b.id 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.id IN ($placeholders)
                ORDER BY FIELD(p.id, " . implode(',', $ids) . ")";
        
        $this->db->query($sql);
        foreach ($ids as $i => $id) {
            $this->db->bind($i + 1, $id);
        }
        
        return $this->db->resultSet();
    }

    public function getRatingAnalysis($productId) {
        $sql = "SELECT rating, COUNT(*) as count FROM reviews WHERE product_id = :id GROUP BY rating ORDER BY rating DESC";
        $this->db->query($sql);
        $this->db->bind(':id', $productId);
        $results = $this->db->resultSet();
        
        $analysis = [
            5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0,
            'total' => 0,
            'average' => 0
        ];
        
        $sum = 0;
        foreach ($results as $row) {
            $analysis[$row['rating']] = $row['count'];
            $analysis['total'] += $row['count'];
            $sum += $row['rating'] * $row['count'];
        }
        
        if ($analysis['total'] > 0) {
            $analysis['average'] = round($sum / $analysis['total'], 1);
        }
        
        return $analysis;
    }

    public function getRecommendations($budget, $purpose) {
        $recommendations = [];
        
        // Find CPU (approx 25% of budget)
        $cpuBudget = $budget * 0.30;
        $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = 3 AND (p.name LIKE '%Intel%' OR p.name LIKE '%AMD%') AND p.price <= :budget ORDER BY p.price DESC LIMIT 1");
        $this->db->bind(':budget', $cpuBudget);
        $cpu = $this->db->single();
        if ($cpu) $recommendations[] = $cpu;

        // Find Mainboard (approx 15% of budget)
        $mbBudget = $budget * 0.20;
        $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = 3 AND p.name LIKE '%Mainboard%' AND p.price <= :budget ORDER BY p.price DESC LIMIT 1");
        $this->db->bind(':budget', $mbBudget);
        $mb = $this->db->single();
        if ($mb) $recommendations[] = $mb;

        // Find RAM (approx 10% of budget)
        $ramBudget = $budget * 0.15;
        $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = 3 AND p.name LIKE '%RAM%' AND p.price <= :budget ORDER BY p.price DESC LIMIT 1");
        $this->db->bind(':budget', $ramBudget);
        $ram = $this->db->single();
        if ($ram) $recommendations[] = $ram;

        // Find GPU (approx 35% of budget)
        $gpuBudget = $budget * 0.45;
        $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = 3 AND (p.name LIKE '%RTX%' OR p.name LIKE '%GTX%' OR p.name LIKE '%RX %') AND p.price <= :budget ORDER BY p.price DESC LIMIT 1");
        $this->db->bind(':budget', $gpuBudget);
        $gpu = $this->db->single();
        if ($gpu) $recommendations[] = $gpu;

        return $recommendations;
    }
    public function getBrandByName($name) {
        $this->db->query("SELECT * FROM brands WHERE name LIKE :name LIMIT 1");
        $this->db->bind(':name', '%' . $name . '%');
        return $this->db->single();
    }
}
