<?php

class ProductController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->categoryModel = $this->model('CategoryModel');
    }

    public function category($id = 1) {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            header('Location: ' . URLROOT);
            exit();
        }

        $filters = [
            'brand' => $_GET['brand'] ?? null,
            'price_min' => $_GET['price_min'] ?? null,
            'price_max' => $_GET['price_max'] ?? null,
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        $limit = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getProductsByCategory($id, $filters, $limit, $offset);
        $totalProducts = $this->productModel->getTotalProductsByCategory($id, $filters);
        $totalPages = ceil($totalProducts / $limit);

        $wishlistModel = $this->model('WishlistModel');
        $wishlistIds = [];
        if (isset($_SESSION['customer_id'])) {
            $wishlistItems = $wishlistModel->getWishlistByCustomer($_SESSION['customer_id']);
            $wishlistIds = array_column($wishlistItems, 'id');
        }

        $brands = $this->productModel->getBrandsByCategory($id);
        $catName = __($category['name']);
        $brandNames = array_column($brands, 'name');
        $brandStr = !empty($brandNames) ? ' từ ' . implode(', ', array_slice($brandNames, 0, 5)) : '';
        $metaDesc = "Danh mục {$catName} chính hãng{$brandStr}. Đầy đủ cấu hình, mẫu mã đa dạng, hiệu năng cao, giá tốt tại TechExpert.";
        $metaKeys = "{$catName}, mua {$catName}, linh kien {$catName}" . (!empty($brandNames) ? ', ' . implode(', ', array_slice($brandNames, 0, 5)) : '');

        $data = [
            'title' => $catName . ' | Cung Cấp Linh Kiện Chính Hãng',
            'meta_description' => $metaDesc,
            'meta_keywords' => $metaKeys,
            'category' => $category,
            'products' => $products,
            'subcategories' => $this->productModel->getSubcategories($id),
            'brands' => $brands,
            'categories' => $this->categoryModel->getCategoriesWithBrands(),
            'filters' => $filters,
            'category_id' => $id,
            'wishlist_ids' => $wishlistIds,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalProducts
            ]
        ];

        $this->view('products/category', $data);
    }


    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT);
            exit();
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: ' . URLROOT);
            exit();
        }

        // Increment View Count
        $this->productModel->incrementViewCount($id);

        // Add to Recently Viewed
        if (!isset($_SESSION['recently_viewed'])) {
            $_SESSION['recently_viewed'] = [];
        }
        // Remove if exists to move to top
        if (($key = array_search($id, $_SESSION['recently_viewed'])) !== false) {
            unset($_SESSION['recently_viewed'][$key]);
        }
        array_unshift($_SESSION['recently_viewed'], $id);
        $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 10);

        $reviews = $this->productModel->getReviewsByProductId($id);
        $ratingAnalysis = $this->productModel->getRatingAnalysis($id);
        $relatedProducts = $this->productModel->getRelatedProducts($id, $product['category_id']);
        
        // Fetch product variants
        $variants = $this->productModel->getProductVariants($id);
        
        $canReview = false;
        $isInWishlist = false;
        if (isset($_SESSION['customer_id'])) {
            $canReview = $this->productModel->hasBoughtProduct($_SESSION['customer_id'], $id);
            $wishlistModel = $this->model('WishlistModel');
            $isInWishlist = $wishlistModel->isInWishlist($_SESSION['customer_id'], $id);
        }

        $prodName = $product['name'];
        $brandName = $product['brand_name'] ?? 'Chính hãng';
        $catName = $product['category_name'] ?? 'Linh kiện';
        $shortDesc = !empty($product['short_description']) ? trim($product['short_description']) : 'Sản phẩm chất lượng cao';
        $metaDesc = mb_strimwidth("Mua ngay {$prodName} từ thương hiệu {$brandName}. {$shortDesc}. Cung cấp bởi TechExpert với giá tốt nhất, bảo hành chính hãng.", 0, 155, "...");
        $metaKeys = "{$prodName}, {$brandName}, {$catName}, mua {$prodName}, linh kien may tinh";

        $data = [
            'title' => "{$prodName} - {$brandName} {$catName} | TechExpert",
            'meta_description' => $metaDesc,
            'meta_keywords' => $metaKeys,
            'og_image' => isset($product['main_image']) ? get_product_image($product['main_image']) : '',
            'product' => $product,
            'images' => $this->productModel->getProductImages($id),
            'reviews' => $reviews,
            'rating_analysis' => $ratingAnalysis,
            'related_products' => $relatedProducts,
            'categories' => $this->categoryModel->getCategoriesWithBrands(),
            'can_review' => $canReview,
            'is_in_wishlist' => $isInWishlist,
            'variants' => $variants
        ];
        
        $this->view('products/detail', $data);
    }

    public function addReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['customer_id'])) {
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            }

            // Kiểm tra bảo mật: Khách hàng phải mua hàng mới được đánh giá
            if (!$this->productModel->hasBoughtProduct($_SESSION['customer_id'], $_POST['product_id'])) {
                header('Location: ' . URLROOT . '/product/detail/' . $_POST['product_id'] . '?error=not_bought');
                exit();
            }

            $review_image = null;
            if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $filename = $_FILES['review_image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $uploadDir = ROOT . '/public/img/reviews/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $newFilename = uniqid('rev_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['review_image']['tmp_name'], $uploadDir . $newFilename)) {
                        $review_image = URLROOT . '/public/img/reviews/' . $newFilename;
                    }
                }
            }

            $data = [
                'customer_id' => $_SESSION['customer_id'],
                'product_id' => $_POST['product_id'],
                'rating' => $_POST['rating'],
                'comment' => trim($_POST['comment']),
                'review_image' => $review_image
            ];

            if ($this->productModel->addReview($data)) {
                // Thông báo cho Admin
                $notificationModel = $this->model('NotificationModel');
                $product = $this->productModel->getProductById($data['product_id']);
                $customerName = $_SESSION['user_name'] ?? 'Khách hàng';
                
                $notificationModel->createNotification(
                    1, 
                    "Đánh giá mới cho " . $product['name'], 
                    "$customerName vừa đánh giá " . $data['rating'] . " sao: \"" . mb_strimwidth($data['comment'], 0, 50, "...") . "\"", 
                    'info'
                );

                header('Location: ' . URLROOT . '/product/detail/' . $data['product_id'] . '#reviews');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function search() {
        $keyword = $_GET['q'] ?? '';
        
        $filters = [
            'brand' => $_GET['brand'] ?? null,
            'price_min' => $_GET['price_min'] ?? null,
            'price_max' => $_GET['price_max'] ?? null,
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        $products = $this->productModel->searchProducts($keyword, $filters);
        $categories = $this->categoryModel->getAllCategories();

        $wishlistIds = [];
        if (isset($_SESSION['customer_id'])) {
            $wishlistModel = $this->model('WishlistModel');
            $wishlist = $wishlistModel->getWishlistByCustomer($_SESSION['customer_id']);
            $wishlistIds = array_column($wishlist, 'id');
        }

        $data = [
            'title' => __('search_results_for_title', 'Kết quả tìm kiếm cho:') . ' ' . $keyword . ' | TechExpert',
            'meta_description' => "Tìm kiếm sản phẩm '{$keyword}' tại cửa hàng TechExpert. Đầy đủ linh kiện cấu hình máy tính chất lượng, giá ưu đãi.",
            'meta_keywords' => "tìm kiếm {$keyword}, mua {$keyword}, {$keyword} gia re",
            'products' => $products,
            'brands' => $this->productModel->getBrandsBySearch($keyword),
            'categories' => $this->categoryModel->getCategoriesWithBrands(),
            'keyword' => $keyword,
            'filters' => $filters,
            'wishlist_ids' => $wishlistIds
        ];

        $this->view('products/search', $data);
    }

    public function compare() {
        $ids = $_GET['ids'] ?? '';
        $idArray = !empty($ids) ? explode(',', $ids) : [];
        
        // Limit to 4 products max for UI sanity
        $idArray = array_slice($idArray, 0, 4);
        
        $products = $this->productModel->getProductsByIds($idArray);
        
        $productNames = array_column($products, 'name');
        $prodStr = !empty($productNames) ? ' giữa ' . implode(' vs ', array_slice($productNames, 0, 3)) : '';
        $metaDesc = "So sánh chi tiết các dòng sản phẩm linh kiện máy tính{$prodStr} tại TechExpert để chọn lựa cấu hình tối ưu nhất.";
        $metaKeys = "so sanh san pham" . (!empty($productNames) ? ', ' . implode(', ', array_slice($productNames, 0, 4)) : '');

        $data = [
            'title' => __('compare_title', 'So sánh sản phẩm') . ' | Đánh Giá Chi Tiết - TechExpert',
            'meta_description' => $metaDesc,
            'meta_keywords' => $metaKeys,
            'products' => $products,
            'categories' => $this->categoryModel->getCategoriesWithBrands()
        ];
        
        $this->view('products/compare', $data);
    }
    public function aiSearch() {
        $prompt = $_REQUEST['prompt'] ?? '';
        if (empty($prompt)) {
            header('Location: ' . URLROOT);
            exit();
        }

        // AI logic to parse prompt
        $filters = [
            'brand' => null,
            'price_min' => null,
            'price_max' => null,
            'sort' => 'newest'
        ];
        $keyword = '';

        $promptLower = mb_strtolower($prompt);
        
        // Extract Category/Keyword
        if (strpos($promptLower, 'laptop') !== false) $keyword = 'laptop';
        elseif (strpos($promptLower, 'pc') !== false || strpos($promptLower, 'máy tính') !== false) $keyword = 'pc';
        elseif (strpos($promptLower, 'màn hình') !== false) $keyword = 'monitor';
        else $keyword = $prompt;

        // Extract Brand
        $brands = ['dell', 'hp', 'asus', 'acer', 'msi', 'lenovo', 'apple', 'gigabyte'];
        foreach ($brands as $b) {
            if (strpos($promptLower, $b) !== false) {
                $brandObj = $this->productModel->getBrandByName($b);
                if ($brandObj) $filters['brand'] = $brandObj['id'];
                break;
            }
        }

        // Extract Price (e.g. "dưới 20 triệu", "tầm 15tr")
        if (preg_match('/dưới\s+(\d+)\s*(triệu|tr)/u', $promptLower, $matches)) {
            $filters['price_max'] = $matches[1] * 1000000;
        } elseif (preg_match('/tầm\s+(\d+)\s*(triệu|tr)/u', $promptLower, $matches)) {
            $filters['price_min'] = ($matches[1] - 2) * 1000000;
            $filters['price_max'] = ($matches[1] + 2) * 1000000;
        } elseif (preg_match('/trên\s+(\d+)\s*(triệu|tr)/u', $promptLower, $matches)) {
            $filters['price_min'] = $matches[1] * 1000000;
        }

        $products = $this->productModel->searchProducts($keyword, $filters);
        
        $wishlistIds = [];
        if (isset($_SESSION['customer_id'])) {
            $wishlistModel = $this->model('WishlistModel');
            $wishlist = $wishlistModel->getWishlistByCustomer($_SESSION['customer_id']);
            $wishlistIds = array_column($wishlist, 'id');
        }

        $data = [
            'title' => __('search_ai_title', 'AI tìm kiếm:') . ' ' . $prompt . ' | TechExpert',
            'meta_description' => "Tìm kiếm thông minh AI với yêu cầu '{$prompt}' tại cửa hàng TechExpert. Lọc các sản phẩm phù hợp nhất.",
            'meta_keywords' => "tìm kiếm ai, {$prompt}",
            'products' => $products,
            'brands' => $this->productModel->getBrandsBySearch($keyword),
            'categories' => $this->categoryModel->getCategoriesWithBrands(),
            'keyword' => $keyword,
            'filters' => $filters,
            'wishlist_ids' => $wishlistIds,
            'ai_prompt' => $prompt
        ];

        $this->view('products/search', $data);
    }

    public function index() {
        $keyword = $_GET['q'] ?? '';
        
        $filters = [
            'brand' => $_GET['brand'] ?? null,
            'price_min' => $_GET['price_min'] ?? null,
            'price_max' => $_GET['price_max'] ?? null,
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        $isSale = isset($_GET['sale']) && $_GET['sale'] == 1;

        if ($isSale) {
            $products = $this->productModel->getOnSaleProducts(12);
            $title = __('sales_products_title', 'Sản phẩm khuyến mãi');
        } else {
            $products = $this->productModel->searchProducts($keyword, $filters);
            $title = __('all_products', 'Tất cả sản phẩm');
        }

        $wishlistIds = [];
        if (isset($_SESSION['customer_id'])) {
            $wishlistModel = $this->model('WishlistModel');
            $wishlist = $wishlistModel->getWishlistByCustomer($_SESSION['customer_id']);
            $wishlistIds = array_column($wishlist, 'id');
        }

        $data = [
            'title' => $title . ' | Linh Kiện Máy Tính TechExpert',
            'meta_description' => "Xem toàn bộ danh mục sản phẩm {$title} tại TechExpert. Lựa chọn linh kiện máy tính chính hãng tốt nhất từ Dell, HP, Asus, MSI...",
            'meta_keywords' => "linh kien may tinh, laptop, computer shop, pc gaming, do hoa, {$title}",
            'products' => $products,
            'brands' => $this->productModel->getAllBrands(),
            'categories' => $this->categoryModel->getCategoriesWithBrands(),
            'keyword' => $keyword,
            'filters' => $filters,
            'wishlist_ids' => $wishlistIds,
            'is_sale' => $isSale
        ];

        $this->view('products/search', $data);
    }
}

