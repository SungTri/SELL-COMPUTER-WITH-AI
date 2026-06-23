<?php

class AdminController extends Controller {
    private $adminModel;
    private $chatbotModel;

    public function __construct() {
        // Kiểm tra xem đã đăng nhập và có phải là Admin (role_id = 1) không
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        $this->adminModel = $this->model('AdminModel');
        $this->chatbotModel = $this->model('ChatbotModel');
    }

    public function index() {
        $revenue = $this->adminModel->getTotalRevenue();
        $todayRevenue = $this->adminModel->getTodayRevenue();
        $ordersCount = $this->adminModel->getTotalOrders();
        $inventoryCount = $this->adminModel->getTotalProducts();
        $usersCount = $this->adminModel->getTotalUsers();
        
        // New Advanced Stats
        $growthData = $this->adminModel->getGrowthStats();
        $revenueByCategory = $this->adminModel->getRevenueByCategory();
        $topCustomers = $this->adminModel->getTopCustomers(5);
        
        $recentOrders = $this->adminModel->getRecentOrders(5);
        $revenueByMonth = $this->adminModel->getRevenueByMonth();
        $orderStatusDist = $this->adminModel->getOrderStatusDistribution();
        $topProducts = $this->adminModel->getTopSellingProducts(5);
        
        // Calculate Growth Rates
        $revGrowth = $growthData['last_month']['revenue'] != 0 
            ? (($growthData['this_month']['revenue'] - $growthData['last_month']['revenue']) / $growthData['last_month']['revenue']) * 100 
            : ($growthData['this_month']['revenue'] > 0 ? 100 : 0);
            
        $orderGrowth = $growthData['last_month']['orders'] != 0 
            ? (($growthData['this_month']['orders'] - $growthData['last_month']['orders']) / $growthData['last_month']['orders']) * 100 
            : ($growthData['this_month']['orders'] > 0 ? 100 : 0);
            
        $userGrowth = $growthData['last_month']['users'] != 0 
            ? (($growthData['this_month']['users'] - $growthData['last_month']['users']) / $growthData['last_month']['users']) * 100 
            : ($growthData['this_month']['users'] > 0 ? 100 : 0);

        // New Metrics
        $lowStockProducts = $this->adminModel->getLowStockProducts(5);
        $revenueByBrand = $this->adminModel->getRevenueByBrand();
        $avgOrderValue = $ordersCount > 0 ? $revenue / $ordersCount : 0;

        $statusMapping = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'shipped' => 'Đã giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        $formattedOrders = [];
        foreach ($recentOrders as $order) {
            $formattedOrders[] = [
                'id' => '#' . $order['id'],
                'raw_id' => $order['id'],
                'customer' => $order['customer'] ?? 'Khách lẻ',
                'date' => date('d/m/Y H:i', strtotime($order['date'])),
                'total' => number_format($order['total'], 0, ',', '.'),
                'status' => $order['status'],
                'status_text' => $statusMapping[strtolower($order['status'])] ?? 'Không rõ'
            ];
        }

        $data = [
            'title' => 'Tổng quan quản trị - TechExpert',
            'stats' => [
                'revenue' => [
                    'value' => number_format($revenue, 0, ',', '.'), 
                    'growth' => ($revGrowth >= 0 ? '+' : '') . round($revGrowth, 1) . '%', 
                    'status' => $revGrowth >= 0 ? 'up' : 'down'
                ],
                'orders' => [
                    'value' => number_format($ordersCount), 
                    'growth' => ($orderGrowth >= 0 ? '+' : '') . round($orderGrowth, 1) . '%', 
                    'status' => $orderGrowth >= 0 ? 'up' : 'down'
                ],
                'inventory' => [
                    'value' => number_format($inventoryCount), 
                    'growth' => 'Ổn định', 
                    'status' => 'stable'
                ],
                'users' => [
                    'value' => number_format($usersCount), 
                    'growth' => ($userGrowth >= 0 ? '+' : '') . round($userGrowth, 1) . '%', 
                    'status' => $userGrowth >= 0 ? 'up' : 'down'
                ]
            ],
            'recent_orders' => $formattedOrders,
            'charts' => [
                'revenue' => $revenueByMonth,
                'revenue_weekly' => $this->adminModel->getRevenueByWeek(8),
                'order_status' => $orderStatusDist,
                'category_revenue' => $revenueByCategory,
                'brand_revenue' => $revenueByBrand
            ],
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'low_stock' => $lowStockProducts,
            'avg_order_value' => $avgOrderValue,
            'today_revenue' => $todayRevenue
        ];
        
        $this->view('admin/dashboard', $data);
    }

    public function addProduct() {
        $categories = $this->adminModel->getAllCategories();
        $brands = $this->adminModel->getAllBrands();
        
        $data = [
            'title' => 'Thêm sản phẩm mới - TechExpert',
            'categories' => $categories,
            'brands' => $brands,
            'errors' => []
        ];
        
        $this->view('admin/add_product', $data);
    }

    public function storeProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and validate
            $data = [
                'name' => trim($_POST['name']),
                'short_description' => trim($_POST['short_description']),
                'detailed_description' => trim($_POST['detailed_description']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'category_id' => trim($_POST['category_id']),
                'brand_id' => trim($_POST['brand_id']),
                'status' => $_POST['status'] ?? 1,
                'main_image' => '',
                'errors' => []
            ];

            // Image handling
            if (!empty($_FILES['main_image']['name'])) {
                $uploadDir = ROOT . '/public/img/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['main_image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['main_image']['tmp_name'], $targetPath)) {
                    $data['main_image'] = URLROOT . '/img/products/' . $fileName;
                }
            } elseif (!empty($_POST['main_image_library'])) {
                $data['main_image'] = trim($_POST['main_image_library']);
            }

            // Simple validation
            if (empty($data['name'])) $data['errors']['name'] = 'Vui lòng nhập tên sản phẩm';
            if (empty($data['price'])) $data['errors']['price'] = 'Vui lòng nhập giá';
            
            if (empty($data['errors'])) {
                $productId = $this->adminModel->createProduct($data);
                if ($productId) {
                    // Handle Secondary Images
                    if (!empty($_FILES['secondary_images']['name'][0])) {
                        $uploadDir = ROOT . '/public/img/products/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                        foreach ($_FILES['secondary_images']['tmp_name'] as $key => $tmp_name) {
                            $fileName = time() . '_sec_' . $key . '_' . basename($_FILES['secondary_images']['name'][$key]);
                            $targetPath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($tmp_name, $targetPath)) {
                                $imagePath = URLROOT . '/img/products/' . $fileName;
                                $this->adminModel->addProductImage($productId, $imagePath);
                            }
                        }
                    }

                    // Handle Library Secondary Images
                    if (!empty($_POST['secondary_images_library'])) {
                        $libImages = json_decode($_POST['secondary_images_library'], true);
                        if (is_array($libImages)) {
                            foreach ($libImages as $imagePath) {
                                if (!empty($imagePath)) {
                                    $this->adminModel->addProductImage($productId, trim($imagePath));
                                }
                            }
                        }
                    }

                    // === TỰ ĐỘNG THÔNG BÁO & GỬI EMAIL KHI THÊM SẢN PHẨM MỚI ===
                    $notifTitle   = '🎉 Sản phẩm mới: ' . $data['name'];
                    $notifContent = 'Cửa hàng vừa bổ sung sản phẩm mới: ' . $data['name'] . '. Giá: ' . number_format($data['price'], 0, ',', '.') . 'đ. Nhấn để xem ngay!';
                    $productUrl   = URLROOT . '/product/detail/' . $productId;

                    // 1. Tạo thông báo trên web cho tất cả user đã đăng ký tài khoản
                    $notificationModel = $this->model('NotificationModel');
                    $notificationModel->notifyAllUsers($notifTitle, $notifContent, 'new_product');

                    // 2. Gửi email thông báo đến tất cả subscriber newsletter
                    $newsletterModel = $this->model('NewsletterModel');
                    $subscribers     = $newsletterModel->getAllSubscribers();
                    if (!empty($subscribers)) {
                        $emailService = new EmailService();
                        $priceFormatted = number_format($data['price'], 0, ',', '.') . 'đ';
                        foreach ($subscribers as $subscriber) {
                            $emailService->sendNewProductEmail(
                                $subscriber['email'],
                                $data['name'],
                                $data['short_description'] ?? '',
                                $priceFormatted,
                                $data['main_image'] ?? '',
                                $productUrl
                            );
                        }
                    }
                    // === HẾT PHẦN THÔNG BÁO ===

                    header('Location: ' . URLROOT . '/admin/products?msg=Thêm sản phẩm thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi lưu dữ liệu';
                }
            }
            
            // If errors, reload view with data
            $data['categories'] = $this->adminModel->getAllCategories();
            $data['brands'] = $this->adminModel->getAllBrands();
            $this->view('admin/add_product', $data);
        }
    }

    public function editProduct($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/products');
            exit();
        }
        $product = $this->adminModel->getAdminProductById($id);
        if (!$product) {
            header('Location: ' . URLROOT . '/admin/products');
            exit();
        }

        $categories = $this->adminModel->getAllCategories();
        $brands = $this->adminModel->getAllBrands();
        
        // Fetch secondary images using ProductModel
        $productModel = $this->model('ProductModel');
        $secondaryImages = $productModel->getProductImages($id);
        
        // Fetch existing variants
        $variants = $this->adminModel->getProductVariants($id);
        
        $data = [
            'title' => 'Chỉnh sửa sản phẩm - ' . $product['name'],
            'product' => $product,
            'secondary_images' => $secondaryImages,
            'categories' => $categories,
            'brands' => $brands,
            'variants' => $variants,
            'errors' => []
        ];
        
        $this->view('admin/edit_product', $data);
    }

    public function updateProduct($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/products');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'short_description' => trim($_POST['short_description']),
                'detailed_description' => trim($_POST['detailed_description']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'category_id' => trim($_POST['category_id']),
                'brand_id' => trim($_POST['brand_id']),
                'status' => $_POST['status'] ?? 1,
                'main_image' => '',
                'errors' => []
            ];

            // Image handling
            if (!empty($_FILES['main_image']['name'])) {
                $uploadDir = ROOT . '/public/img/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['main_image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['main_image']['tmp_name'], $targetPath)) {
                    $data['main_image'] = URLROOT . '/img/products/' . $fileName;
                }
            } elseif (!empty($_POST['main_image_library'])) {
                $data['main_image'] = trim($_POST['main_image_library']);
            }

            if (empty($data['name'])) $data['errors']['name'] = 'Vui lòng nhập tên sản phẩm';
            
            if (empty($data['errors'])) {
                if ($this->adminModel->updateProduct($data)) {
                    // Handle new Secondary Images if any
                    if (!empty($_FILES['secondary_images']['name'][0])) {
                        $uploadDir = ROOT . '/public/img/products/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                        foreach ($_FILES['secondary_images']['tmp_name'] as $key => $tmp_name) {
                            $fileName = time() . '_sec_' . $key . '_' . basename($_FILES['secondary_images']['name'][$key]);
                            $targetPath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($tmp_name, $targetPath)) {
                                $imagePath = URLROOT . '/img/products/' . $fileName;
                                $this->adminModel->addProductImage($id, $imagePath);
                            }
                        }
                    }

                    // Handle Library Secondary Images
                    if (!empty($_POST['secondary_images_library'])) {
                        $libImages = json_decode($_POST['secondary_images_library'], true);
                        if (is_array($libImages)) {
                            foreach ($libImages as $imagePath) {
                                if (!empty($imagePath)) {
                                    $this->adminModel->addProductImage($id, trim($imagePath));
                                }
                            }
                        }
                    }

                    // Handle Deleted Secondary Images
                    if (!empty($_POST['deleted_images']) && is_array($_POST['deleted_images'])) {
                        foreach ($_POST['deleted_images'] as $imgId) {
                            $img = $this->adminModel->getProductImageById($imgId);
                            if ($img) {
                                $imagePath = $img['image_path'];
                                $fileName = basename($imagePath);
                                $filePath = ROOT . '/public/img/products/' . $fileName;
                                if (file_exists($filePath)) {
                                    @unlink($filePath);
                                }
                                $this->adminModel->deleteProductImage($imgId);
                            }
                        }
                    }

                    // Save variants
                    $variantsJson = $_POST['variants_json'] ?? '';
                    $variants = !empty($variantsJson) ? json_decode($variantsJson, true) : [];
                    $this->adminModel->saveProductVariants($id, $variants);

                    header('Location: ' . URLROOT . '/admin/products?msg=Cập nhật sản phẩm thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi cập nhật';
                }
            }
            
            $data['categories'] = $this->adminModel->getAllCategories();
            $data['brands'] = $this->adminModel->getAllBrands();
            $data['product'] = $this->adminModel->getAdminProductById($id);
            $this->view('admin/edit_product', $data);
        }
    }

    public function deleteProduct($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/products');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['confirm'])) {
            if ($this->adminModel->deleteProduct($id)) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
                    exit();
                }
                header('Location: ' . URLROOT . '/admin/products?msg=Xóa sản phẩm thành công');
                exit();
            }
        }
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm']);
            exit();
        }
        
        header('Location: ' . URLROOT . '/admin/products?error=Không thể xóa sản phẩm');
        exit();
    }

    public function categories() {
        $categories = $this->adminModel->getAllCategories();
        $data = [
            'title' => 'Quản lý danh mục - TechExpert',
            'categories' => $categories
        ];
        $this->view('admin/categories', $data);
    }

    public function addCategory() {
        $parentCategories = $this->adminModel->getParentCategories();
        $data = [
            'title' => 'Thêm danh mục mới',
            'parent_categories' => $parentCategories,
            'name' => '',
            'description' => '',
            'errors' => []
        ];
        $this->view('admin/add_category', $data);
    }

    public function storeCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
                'errors' => []
            ];

            if (empty($data['name'])) $data['errors']['name'] = 'Vui lòng nhập tên danh mục';

            if (empty($data['errors'])) {
                if ($this->adminModel->addCategory($data)) {
                    header('Location: ' . URLROOT . '/admin/categories?msg=Thêm danh mục thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi lưu dữ liệu';
                }
            }
            $data['parent_categories'] = $this->adminModel->getParentCategories();
            $this->view('admin/add_category', $data);
        }
    }

    public function editCategory($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/categories');
            exit();
        }
        $category = $this->adminModel->getCategoryById($id);
        if (!$category) {
            header('Location: ' . URLROOT . '/admin/categories');
            exit();
        }

        $parentCategories = $this->adminModel->getParentCategories($id);
        $data = [
            'title' => 'Chỉnh sửa danh mục',
            'category' => $category,
            'parent_categories' => $parentCategories,
            'errors' => []
        ];
        $this->view('admin/edit_category', $data);
    }

    public function updateCategory($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
                'errors' => []
            ];

            if (empty($data['name'])) $data['errors']['name'] = 'Vui lòng nhập tên danh mục';

            if (empty($data['errors'])) {
                if ($this->adminModel->updateCategory($data)) {
                    header('Location: ' . URLROOT . '/admin/categories?msg=Cập nhật danh mục thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi cập nhật';
                }
            }
            $data['category'] = $this->adminModel->getCategoryById($id);
            $data['parent_categories'] = $this->adminModel->getParentCategories($id);
            $this->view('admin/edit_category', $data);
        }
    }

    public function deleteCategory($id) {
        if ($this->adminModel->deleteCategory($id)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Xóa danh mục thành công']);
                exit();
            }
            header('Location: ' . URLROOT . '/admin/categories?msg=Xóa danh mục thành công');
            exit();
        } else {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Không thể xóa danh mục']);
                exit();
            }
            header('Location: ' . URLROOT . '/admin/categories?error=Không thể xóa danh mục');
            exit();
        }
    }

    public function products() {
        $category = $_GET['category'] ?? '';
        $brand = $_GET['brand'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        
        $products = $this->adminModel->getAdminProducts($search, $category, $brand, $limit, $offset);
        $totalProducts = $this->adminModel->getAdminProductsCount($search, $category, $brand);
        $totalPages = ceil($totalProducts / $limit);
        $categories = $this->adminModel->getAllCategories();
        $brands = $this->adminModel->getAllBrands();

        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'category' => $product['category_name'],
                'brand' => $product['brand_name'],
                'price' => number_format($product['price'], 0, ',', '.'),
                'stock' => $product['stock'],
                'status' => $product['status'],
                'image' => $product['main_image']
            ];
        }

        $data = [
            'title' => 'Quản lý sản phẩm - TechExpert',
            'products' => $formattedProducts,
            'categories' => $categories,
            'brands' => $brands,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalProducts,
                'limit' => $limit,
                'start_record' => ($totalProducts > 0) ? $offset + 1 : 0,
                'end_record' => min($offset + $limit, $totalProducts)
            ],
            'filters' => [
                'category' => $category,
                'brand' => $brand,
                'search' => $search
            ]
        ];
        
        $this->view('admin/products', $data);
    }

    public function orders() {
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        
        $orders = $this->adminModel->getAllOrders($status, $search, $limit, $offset);
        $totalOrders = $this->adminModel->getOrdersCount($status, $search);
        $totalPages = ceil($totalOrders / $limit);
        
        $statusMapping = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'shipped' => 'Đã giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        $formattedOrders = [];
        foreach ($orders as $order) {
            $items = $this->adminModel->getOrderItems($order['id']);
            $firstItem = !empty($items) ? $items[0] : null;
            $prodInfo = "";
            if ($firstItem) {
                $productId = isset($firstItem['product_id']) ? $firstItem['product_id'] : $firstItem['id'];
                $prodInfo = "P" . $productId . "x" . $firstItem['quantity'];
                if (count($items) > 1) {
                    $prodInfo .= "+";
                }
            }
            $userId = $order['user_id'] ?? 0;
            $orderTime = !empty($order['date']) ? strtotime($order['date']) : time();
            $timeStr = date('mdHi', $orderTime); // MMDDHHMM

            $transactionCode = "DH" . $order['id'];
            if ($userId) {
                $transactionCode .= "-U" . $userId;
            }
            if ($prodInfo) {
                $transactionCode .= "-" . $prodInfo;
            }
            $transactionCode .= "-" . $timeStr;

            $formattedOrders[] = [
                'id' => '#' . $order['id'],
                'raw_id' => $order['id'],
                'customer' => $order['customer'] ?? 'Khách lẻ',
                'user_id' => $userId,
                'transaction_code' => $transactionCode,
                'date' => date('d/m/Y H:i', strtotime($order['date'])),
                'total' => number_format($order['total'], 0, ',', '.'),
                'payment_method' => $order['payment_method'],
                'status' => $order['status'],
                'status_text' => $statusMapping[strtolower($order['status'])] ?? 'Không rõ'
            ];
        }

        $data = [
            'title' => 'Quản lý đơn hàng - TechExpert',
            'orders' => $formattedOrders,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalOrders,
                'limit' => $limit,
                'start_record' => ($totalOrders > 0) ? $offset + 1 : 0,
                'end_record' => min($offset + $limit, $totalOrders)
            ],
            'filters' => [
                'status' => $status,
                'search' => $search
            ]
        ];
        
        $this->view('admin/orders', $data);
    }

    public function orderDetail($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/orders');
            exit();
        }
        $order = $this->adminModel->getOrderById($id);
        
        if (!$order) {
            header('Location: ' . URLROOT . '/admin/orders');
            exit();
        }

        $items = $this->adminModel->getOrderItems($id);
        
        $statusMapping = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'shipped' => 'Đã giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        try {
            $order['formatted_date'] = date('d/m/Y H:i', strtotime($order['ordered_at'] ?? 'now'));
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price_at_purchase'] * $item['quantity'];
            }
            $total = (float)($order['total_amount'] ?? 0);
            $order['formatted_total'] = number_format($total, 0, ',', '.');
            $order['formatted_subtotal'] = number_format($subtotal, 0, ',', '.');
            $status_key = strtolower($order['order_status'] ?? 'pending');
            $order['status_text'] = $statusMapping[$status_key] ?? 'Không rõ';

            // Generate transaction code
            $firstItem = !empty($items) ? $items[0] : null;
            $prodInfo = "";
            if ($firstItem) {
                $productId = isset($firstItem['product_id']) ? $firstItem['product_id'] : $firstItem['id'];
                $prodInfo = "P" . $productId . "x" . $firstItem['quantity'];
                if (count($items) > 1) {
                    $prodInfo .= "+";
                }
            }
            $userId = $order['user_id'] ?? 0;
            $orderTime = !empty($order['ordered_at']) ? strtotime($order['ordered_at']) : time();
            $timeStr = date('mdHi', $orderTime); // MMDDHHMM

            $transactionCode = "DH" . $order['id'];
            if ($userId) {
                $transactionCode .= "-U" . $userId;
            }
            if ($prodInfo) {
                $transactionCode .= "-" . $prodInfo;
            }
            $transactionCode .= "-" . $timeStr;

            $order['transaction_code'] = $transactionCode;
        } catch (Exception $e) {
            die("Processing error: " . $e->getMessage());
        }

        $data = [
            'title' => 'Chi tiết đơn hàng #' . $order['id'],
            'order' => $order,
            'items' => $items
        ];

        $this->view('admin/order_detail', $data);
    }

    public function updateOrderStatus($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/orders');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'] ?? 'pending';
            
            // Get current order to check previous status for stock restoration
            $order = $this->adminModel->getOrderById($id);
            $oldStatus = strtolower($order['order_status']);
            $newStatus = strtolower($status);

            // Validate status
            $validStatuses = ['pending', 'shipping', 'delivered', 'cancelled'];
            if (in_array($newStatus, $validStatuses)) {
                if ($this->adminModel->updateOrderStatus($id, $newStatus)) {
                    // Logic: Hoàn kho khi hủy đơn
                    if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                        $items = $this->adminModel->getOrderItems($id);
                        $productModel = $this->model('ProductModel');
                        foreach ($items as $item) {
                            $productModel->increaseStock($item['product_id'], $item['quantity']);
                        }
                    }
                    // Logic: Trừ kho nếu phục hồi đơn từ trạng thái đã hủy (Admin bấm nhầm và muốn khôi phục)
                    elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                        $items = $this->adminModel->getOrderItems($id);
                        $productModel = $this->model('ProductModel');
                        foreach ($items as $item) {
                            $productModel->decreaseStock($item['product_id'], $item['quantity']);
                        }
                    }
                }
            }
            
            header('Location: ' . URLROOT . '/admin/orderDetail/' . $id);
            exit();
        }
    }

    public function updatePaymentStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['payment_status'] ?? 'Pending';
            
            // Validate status
            $validStatuses = ['Pending', 'Paid', 'Refunded'];
            if (in_array($status, $validStatuses)) {
                $this->adminModel->updatePaymentStatus($id, $status);
            }
            
            header('Location: ' . URLROOT . '/admin/orderDetail/' . $id);
            exit();
        }
    }

    public function promotions() {
        $vouchers = $this->adminModel->getAllVouchers();
        $data = [
            'title' => 'Quản lý Khuyến mãi - TechExpert',
            'vouchers' => $vouchers
        ];
        $this->view('admin/promotions', $data);
    }

    public function addPromotion() {
        $data = [
            'title' => 'Thêm mã khuyến mãi mới',
            'errors' => []
        ];
        $this->view('admin/add_promotion', $data);
    }

    public function storePromotion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => strtoupper(trim($_POST['code'])),
                'description' => trim($_POST['description']),
                'discount_percentage' => !empty($_POST['discount_percentage']) ? $_POST['discount_percentage'] : null,
                'discount_amount' => !empty($_POST['discount_amount']) ? $_POST['discount_amount'] : null,
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'status' => $_POST['status'] ?? 1,
                'errors' => []
            ];

            if (empty($data['code'])) $data['errors']['code'] = 'Vui lòng nhập mã code';
            if (empty($data['discount_percentage']) && empty($data['discount_amount'])) {
                $data['errors']['discount'] = 'Vui lòng nhập ít nhất một loại giảm giá';
            }

            if (empty($data['errors'])) {
                if ($this->adminModel->addVoucher($data)) {
                    header('Location: ' . URLROOT . '/admin/promotions?msg=Thêm mã khuyến mãi thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi lưu dữ liệu';
                }
            }
            $this->view('admin/add_promotion', $data);
        }
    }

    public function editPromotion($id) {
        $voucher = $this->adminModel->getVoucherById($id);
        if (!$voucher) {
            header('Location: ' . URLROOT . '/admin/promotions');
            exit();
        }

        $data = [
            'title' => 'Chỉnh sửa mã khuyến mãi',
            'voucher' => $voucher,
            'errors' => []
        ];
        $this->view('admin/edit_promotion', $data);
    }

    public function updatePromotion($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'code' => strtoupper(trim($_POST['code'])),
                'description' => trim($_POST['description']),
                'discount_percentage' => !empty($_POST['discount_percentage']) ? $_POST['discount_percentage'] : null,
                'discount_amount' => !empty($_POST['discount_amount']) ? $_POST['discount_amount'] : null,
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'status' => $_POST['status'] ?? 1,
                'errors' => []
            ];

            if (empty($data['code'])) $data['errors']['code'] = 'Vui lòng nhập mã code';

            if (empty($data['errors'])) {
                if ($this->adminModel->updateVoucher($data)) {
                    header('Location: ' . URLROOT . '/admin/promotions?msg=Cập nhật mã khuyến mãi thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi cập nhật';
                }
            }
            $data['voucher'] = $this->adminModel->getVoucherById($id);
            $this->view('admin/edit_promotion', $data);
        }
    }

    public function deletePromotion($id) {
        if ($this->adminModel->deleteVoucher($id)) {
            header('Location: ' . URLROOT . '/admin/promotions?msg=Xóa mã khuyến mãi thành công');
        } else {
            header('Location: ' . URLROOT . '/admin/promotions?error=Không thể xóa mã khuyến mãi này');
        }
        exit();
    }

    public function users() {
        $roleId = $_GET['role'] ?? null;
        $search = $_GET['search'] ?? '';
        $isAjax = isset($_GET['ajax']);
        
        $users = $this->adminModel->getAllUsers($roleId, $search);
        $roles = $this->adminModel->getAllRoles();
        
        $data = [
            'title' => 'Quản lý Tài khoản - TechExpert',
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'role' => $roleId,
                'search' => $search
            ]
        ];

        if ($isAjax) {
            $this->view('admin/users_table', $data);
        } else {
            $this->view('admin/users', $data);
        }
    }

    public function userDetail($id) {
        $user = $this->adminModel->getAdminUserById($id);
        if (!$user) {
            header('Location: ' . URLROOT . '/admin/users?error=Không tìm thấy người dùng');
            exit();
        }

        $orders = $this->adminModel->getUserOrders($id);
        $reviews = $this->adminModel->getUserReviews($id);
        $totalSpent = $this->adminModel->getUserTotalSpent($id);

        $data = [
            'title' => 'Chi tiết Người dùng - ' . ($user['full_name'] ?: $user['email']),
            'user' => $user,
            'orders' => $orders,
            'reviews' => $reviews,
            'totalSpent' => $totalSpent
        ];

        $this->view('admin/user_detail', $data);
    }

    public function updateUserStatus($id) {
        if (isset($_GET['status'])) {
            $status = (int)$_GET['status'];
            if ($this->adminModel->updateUserStatus($id, $status)) {
                header('Location: ' . URLROOT . '/admin/users?msg=Cập nhật trạng thái thành công');
                exit();
            }
        }
        header('Location: ' . URLROOT . '/admin/users?error=Không thể cập nhật trạng thái');
        exit();
    }

    public function deleteUser($id) {
        // Ngăn chặn admin tự xóa chính mình
        if ($id == $_SESSION['user_id']) {
            header('Location: ' . URLROOT . '/admin/users?error=Bạn không thể tự xóa tài khoản của chính mình');
            exit();
        }

        if ($this->adminModel->deleteUserPermanently($id)) {
            header('Location: ' . URLROOT . '/admin/users?msg=Tài khoản đã được xóa vĩnh viễn khỏi hệ thống');
        } else {
            header('Location: ' . URLROOT . '/admin/users?error=Không thể xóa tài khoản');
        }
        exit();
    }

    public function updateUserRole($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check self-demotion
            if ($id == $_SESSION['user_id']) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Bạn không thể tự thay đổi vai trò của chính mình']);
                exit();
            }

            $roleId = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 0;
            if ($roleId > 0) {
                if ($this->adminModel->updateUserRole($id, $roleId)) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Cập nhật vai trò tài khoản thành công']);
                    exit();
                }
            }

            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ hoặc lỗi cập nhật cơ sở dữ liệu']);
            exit();
        }
        header('Location: ' . URLROOT . '/admin/users');
        exit();
    }

    public function exportOrders() {
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        
        // Lấy tất cả dữ liệu (không giới hạn pagination)
        $orders = $this->adminModel->getAllOrders($status, $search, 100000, 0);
        
        $statusMapping = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'shipped' => 'Đã giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        // Tính toán các số liệu KPI khái quát
        $totalOrdersCount = count($orders);
        $totalRevenueSum = 0;
        $pendingCount = 0;
        $processingCount = 0;
        $completedCount = 0;
        $cancelledCount = 0;

        foreach ($orders as $order) {
            $orderStatus = strtolower($order['status']);
            if ($orderStatus !== 'cancelled' && $orderStatus !== 'pending') {
                $totalRevenueSum += $order['total'];
            }
            if ($orderStatus === 'pending') {
                $pendingCount++;
            } elseif ($orderStatus === 'processing') {
                $processingCount++;
            } elseif ($orderStatus === 'cancelled') {
                $cancelledCount++;
            } else {
                $completedCount++; // delivered, shipped, completed
            }
        }

        // Thiết lập header xuất file Excel chuyên nghiệp .xls
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=danh_sach_don_hang_" . date('Y-m-d_H-i') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Khởi dựng HTML cấu trúc Excel XML hỗ trợ hiển thị lưới Gridlines và font chữ đẹp mắt
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head>';
        echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Danh sách đơn hàng</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '<style>';
        echo 'body { font-family: "Segoe UI", system-ui, -apple-system, sans-serif; color: #334155; margin: 20px; }';
        echo 'table { border-collapse: collapse; margin-bottom: 25px; width: 100%; }';
        echo 'th { background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; text-align: left; font-size: 13px; }';
        echo 'td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; font-size: 13px; color: #475569; }';
        echo '.bold { font-weight: bold; }';
        echo '.title { font-size: 22px; font-weight: bold; color: #0f172a; padding-bottom: 5px; }';
        echo '.subtitle { font-size: 12px; color: #64748b; padding-bottom: 15px; }';
        echo '.section-header { background-color: #f8fafc; color: #0f172a; font-size: 14px; font-weight: bold; border-left: 5px solid #3b82f6; padding: 10px 15px; margin-top: 15px; margin-bottom: 15px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; border-radius: 4px; }';
        echo '.kpi-table { width: 100%; border: none; margin-bottom: 30px; }';
        echo '.kpi-card { background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 18px; text-align: center; }';
        echo '.kpi-title { font-size: 11px; color: #0369a1; text-transform: uppercase; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.5px; }';
        echo '.kpi-value { font-size: 20px; font-weight: bold; color: #0284c7; }';
        echo '.zebra-row { background-color: #f8fafc; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        // Tiêu đề lớn
        echo '<table style="border:none; margin-bottom:10px;">';
        echo '<tr><td style="border:none; padding:0;" colspan="8"><div class="title" style="font-size: 22px; font-weight: bold; color: #0f172a;">BÁO CÁO DANH SÁCH QUẢN LÝ ĐƠN HÀNG</div></td></tr>';
        $filterDesc = 'Tất cả trạng thái';
        if (!empty($status)) {
            $filterDesc = 'Trạng thái: ' . ($statusMapping[strtolower($status)] ?? $status);
        }
        if (!empty($search)) {
            $filterDesc .= ' | Từ khóa tìm kiếm: "' . htmlspecialchars($search) . '"';
        }
        echo '<tr><td style="border:none; padding:0; padding-bottom:15px;" colspan="8"><div class="subtitle" style="font-size: 12px; color: #64748b;">Hệ thống bán hàng TechExpert | Bộ lọc: ' . $filterDesc . ' | Ngày lập: ' . date('d/m/Y H:i:s') . '</div></td></tr>';
        echo '</table>';
        
        // I. KPI Summary Cards
        echo '<div class="section-header" style="background-color: #f8fafc; color: #0f172a; font-size: 14px; font-weight: bold; border-left: 5px solid #16a34a; padding: 10px 15px; margin-top: 15px; margin-bottom: 15px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">I. THÔNG TIN KHÁI QUÁT ĐƠN HÀNG</div>';
        echo '<table class="kpi-table" style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
        echo '<tr>';
        echo '<td align="center" style="background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 8px 12px; text-align: center; font-size: 11px; color: #475569; font-weight: bold; text-transform: uppercase; width: 16%;">Tổng số đơn</td>';
        echo '<td align="center" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 8px 12px; text-align: center; font-size: 11px; color: #0369a1; font-weight: bold; text-transform: uppercase; width: 20%;">Doanh thu lọc được</td>';
        echo '<td align="center" style="background-color: #fffbeb; border: 1px solid #fef3c7; padding: 8px 12px; text-align: center; font-size: 11px; color: #b45309; font-weight: bold; text-transform: uppercase; width: 16%;">Đơn chờ xử lý</td>';
        echo '<td align="center" style="background-color: #eff6ff; border: 1px solid #dbeafe; padding: 8px 12px; text-align: center; font-size: 11px; color: #1d4ed8; font-weight: bold; text-transform: uppercase; width: 16%;">Đơn đang xử lý</td>';
        echo '<td align="center" style="background-color: #ecfdf5; border: 1px solid #d1fae5; padding: 8px 12px; text-align: center; font-size: 11px; color: #047857; font-weight: bold; text-transform: uppercase; width: 16%;">Đơn thành công</td>';
        echo '<td align="center" style="background-color: #fef2f2; border: 1px solid #fee2e2; padding: 8px 12px; text-align: center; font-size: 11px; color: #b91c1c; font-weight: bold; text-transform: uppercase; width: 16%;">Đơn đã hủy</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td align="center" style="background-color: #ffffff; border: 1px solid #cbd5e1; padding: 12px; text-align: center; font-size: 18px; font-weight: bold; color: #0f172a;">' . number_format($totalOrdersCount) . '</td>';
        echo '<td align="center" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 12px; text-align: center; font-size: 18px; font-weight: bold; color: #0284c7;">' . number_format($totalRevenueSum, 0, ',', '.') . ' ₫</td>';
        echo '<td align="center" style="background-color: #fffbeb; border: 1px solid #fef3c7; padding: 12px; text-align: center; font-size: 18px; font-weight: bold; color: #d97706;">' . number_format($pendingCount) . '</td>';
        echo '<td align="center" style="background-color: #eff6ff; border: 1px solid #dbeafe; padding: 12px; text-align: center; font-size: 18px; font-weight: bold; color: #2563eb;">' . number_format($processingCount) . '</td>';
        echo '<td align="center" style="background-color: #ecfdf5; border: 1px solid #d1fae5; padding: 12px; text-align: center; font-size: 18px; font-weight: bold; color: #059669;">' . number_format($completedCount) . '</td>';
        echo '<td align="center" style="background-color: #fef2f2; border: 1px solid #fee2e2; padding: 12px; text-align: center; font-size: 18px; font-weight: bold; color: #dc2626;">' . number_format($cancelledCount) . '</td>';
        echo '</tr>';
        echo '</table>';
        
        // II. Chi tiết danh sách đơn hàng
        echo '<div class="section-header" style="background-color: #f8fafc; color: #0f172a; font-size: 14px; font-weight: bold; border-left: 5px solid #16a34a; padding: 10px 15px; margin-top: 15px; margin-bottom: 15px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">II. CHI TIẾT DANH SÁCH ĐƠN HÀNG</div>';
        echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
        
        echo '<tr>';
        echo '<th align="center" style="width: 10%; text-align: center; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Mã đơn hàng</th>';
        echo '<th align="center" style="width: 10%; text-align: center; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Mã người dùng</th>';
        echo '<th align="center" style="width: 20%; text-align: center; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Mã giao dịch</th>';
        echo '<th align="left" style="width: 20%; text-align: left; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Khách hàng</th>';
        echo '<th align="center" style="width: 14%; text-align: center; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Ngày đặt</th>';
        echo '<th align="right" style="width: 10%; text-align: right; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Tổng tiền</th>';
        echo '<th align="center" style="width: 8%; text-align: center; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Thanh toán</th>';
        echo '<th align="center" style="width: 8%; text-align: center; background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; font-size: 13px;">Trạng thái</th>';
        echo '</tr>';
        
        $zebra = false;
        foreach ($orders as $order) {
            $zebraBg = $zebra ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
            
            // Khởi tạo mã giao dịch giống view hiển thị
            $items = $this->adminModel->getOrderItems($order['id']);
            $firstItem = !empty($items) ? $items[0] : null;
            $prodInfo = "";
            if ($firstItem) {
                $productId = isset($firstItem['product_id']) ? $firstItem['product_id'] : $firstItem['id'];
                $prodInfo = "P" . $productId . "x" . $firstItem['quantity'];
                if (count($items) > 1) {
                    $prodInfo .= "+";
                }
            }
            $userId = $order['user_id'] ?? 0;
            $orderTime = !empty($order['date']) ? strtotime($order['date']) : time();
            $timeStr = date('mdHi', $orderTime); // MMDDHHMM

            $transactionCode = "DH" . $order['id'];
            if ($userId) {
                $transactionCode .= "-U" . $userId;
            }
            if ($prodInfo) {
                $transactionCode .= "-" . $prodInfo;
            }
            $transactionCode .= "-" . $timeStr;

            $statusLower = strtolower($order['status']);
            $statusText = $statusMapping[$statusLower] ?? 'Không rõ';
            
            // Set styles dynamically for order status
            $statusColor = "#059669"; // Green (delivered, completed, shipped)
            if ($statusLower === 'pending') {
                $statusColor = "#d97706"; // Orange
            } elseif ($statusLower === 'processing') {
                $statusColor = "#2563eb"; // Blue
            } elseif ($statusLower === 'shipping') {
                $statusColor = "#0891b2"; // Cyan
            } elseif ($statusLower === 'cancelled') {
                $statusColor = "#dc2626"; // Red
            }

            echo '<tr>';
            echo '<td align="center" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: center; font-weight: bold; font-size: 13px;">#' . $order['id'] . '</td>';
            echo '<td align="center" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: center; font-weight: bold; color: #4f46e5; font-family: monospace; font-size: 13px;">U' . $userId . '</td>';
            echo '<td align="center" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: center; font-family: monospace; font-size: 13px;">' . htmlspecialchars($transactionCode) . '</td>';
            echo '<td align="left" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: left; font-weight: bold; color: #1e293b; font-size: 13px;">' . htmlspecialchars($order['customer'] ?? 'Khách lẻ') . '</td>';
            echo '<td align="center" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: center; font-size: 13px;">' . date('d/m/Y H:i', strtotime($order['date'])) . '</td>';
            echo '<td align="right" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: right; font-weight: bold; color: #0453cd; font-size: 13px;">' . number_format($order['total'], 0, ',', '.') . ' ₫</td>';
            echo '<td align="center" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: center; font-size: 13px;">' . htmlspecialchars($order['payment_method']) . '</td>';
            echo '<td align="center" style="' . $zebraBg . ' border: 1px solid #cbd5e1; padding: 10px 12px; text-align: center; font-size: 13px; font-weight: bold; color: ' . $statusColor . ';">' . htmlspecialchars($statusText) . '</td>';
            echo '</tr>';
            
            $zebra = !$zebra;
        }
        
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit();
    }

    public function getRevenueChartData() {
        $startDate = $_GET['start'] ?? date('Y-m-d', strtotime('-6 months'));
        $endDate = $_GET['end'] ?? date('Y-m-d');
        
        $data = $this->adminModel->getRevenueByDateRange($startDate, $endDate);
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function getDashboardStatsData() {
        $startDate = $_GET['start'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end'] ?? date('Y-m-d');
        
        $revenue_total = $this->adminModel->getTotalRevenue($startDate, $endDate);
        $orders_count = $this->adminModel->getTotalOrders($startDate, $endDate);
        $new_users = $this->adminModel->getNewUsersCount($startDate, $endDate);
        $inventory_count = $this->adminModel->getTotalProducts();
        
        // Calculate Growth Rates by comparing to the previous equivalent period
        $start_time = strtotime($startDate);
        $end_time = strtotime($endDate);
        $diff = $end_time - $start_time;
        if ($diff < 0) $diff = 0;
        
        $prevStart = date('Y-m-d', $start_time - $diff - 86400);
        $prevEnd = date('Y-m-d', $start_time - 86400);
        
        $prevRevenue = $this->adminModel->getTotalRevenue($prevStart, $prevEnd);
        $revGrowth = $prevRevenue != 0 
            ? (($revenue_total - $prevRevenue) / $prevRevenue) * 100 
            : ($revenue_total > 0 ? 100 : 0);
            
        $prevOrders = $this->adminModel->getTotalOrders($prevStart, $prevEnd);
        $orderGrowth = $prevOrders != 0 
            ? (($orders_count - $prevOrders) / $prevOrders) * 100 
            : ($orders_count > 0 ? 100 : 0);
            
        $prevUsers = $this->adminModel->getNewUsersCount($prevStart, $prevEnd);
        $userGrowth = $prevUsers != 0 
            ? (($new_users - $prevUsers) / $prevUsers) * 100 
            : ($new_users > 0 ? 100 : 0);
            
        $avg_order_value = $orders_count > 0 ? $revenue_total / $orders_count : 0;
        
        // Fetch chart data
        $revenueChartData = $this->adminModel->getRevenueByDateRange($startDate, $endDate);
        $categoryRevenue = $this->adminModel->getRevenueByCategory($startDate, $endDate);
        $brandRevenue = $this->adminModel->getRevenueByBrand($startDate, $endDate);
        $orderStatusDist = $this->adminModel->getOrderStatusDistribution($startDate, $endDate);
        
        // Top lists
        $topProducts = $this->adminModel->getTopSellingProducts(5, $startDate, $endDate);
        foreach ($topProducts as &$p) {
            if (!empty($p['image']) && !filter_var($p['image'], FILTER_VALIDATE_URL)) {
                $p['image'] = URLROOT . '/public/uploads/' . $p['image'];
            }
            $p['price_formatted'] = number_format($p['price'], 0, ',', '.');
        }
        
        $topCustomers = $this->adminModel->getTopCustomers(5, $startDate, $endDate);
        foreach ($topCustomers as &$c) {
            $c['total_spent_formatted'] = number_format($c['total_spent'], 0, ',', '.');
            $c['avatar_url'] = ($c['avatar'] ?? null) ?: 'https://ui-avatars.com/api/?name=' . urlencode($c['name']) . '&background=random';
        }
        
        $lowStockProducts = $this->adminModel->getLowStockProducts(5);
        foreach ($lowStockProducts as &$lp) {
            if (!empty($lp['image']) && !filter_var($lp['image'], FILTER_VALIDATE_URL)) {
                $lp['image'] = URLROOT . '/public/uploads/' . $lp['image'];
            }
        }
        
        $data = [
            'stats' => [
                'revenue' => [
                    'value' => number_format($revenue_total, 0, ',', '.'),
                    'growth' => ($revGrowth >= 0 ? '+' : '') . round($revGrowth, 1) . '%',
                    'status' => $revGrowth >= 0 ? 'up' : 'down'
                ],
                'orders' => [
                    'value' => number_format($orders_count),
                    'growth' => ($orderGrowth >= 0 ? '+' : '') . round($orderGrowth, 1) . '%',
                    'status' => $orderGrowth >= 0 ? 'up' : 'down'
                ],
                'users' => [
                    'value' => number_format($new_users),
                    'growth' => ($userGrowth >= 0 ? '+' : '') . round($userGrowth, 1) . '%',
                    'status' => $userGrowth >= 0 ? 'up' : 'down'
                ],
                'avg_order' => [
                    'value' => number_format($avg_order_value, 0, ',', '.')
                ],
                'inventory' => [
                    'value' => number_format($inventory_count),
                    'growth' => 'Ổn định',
                    'status' => 'stable'
                ]
            ],
            'charts' => [
                'revenue' => $revenueChartData,
                'category_revenue' => $categoryRevenue,
                'brand_revenue' => $brandRevenue,
                'order_status' => $orderStatusDist
            ],
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'low_stock' => $lowStockProducts
        ];
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function distributeVoucher($id) {
        $voucher = $this->adminModel->getVoucherById($id);
        
        if (!$voucher) {
            header('Location: ' . URLROOT . '/admin/promotions?error=VoucherNotFound');
            exit();
        }

        $users = $this->adminModel->getVoucherSubscriberEmails();
        
        if (empty($users)) {
            header('Location: ' . URLROOT . '/admin/promotions?error=NoUsersFound');
            exit();
        }

        $successCount = 0;
        foreach ($users as $user) {
            $title = "Mã giảm giá mới: " . $voucher['code'];
            $content = "Bạn đã nhận được ưu đãi " . $voucher['description'] . ". Mã: " . $voucher['code'];
            
            // Send notification
            $notified = $this->adminModel->createNotification($user['id'], $title, $content);
            
            // Automatically save to user's vouchers list
            $saved = $this->adminModel->saveUserVoucher($user['id'], $voucher['id']);
            
            if ($notified || $saved) {
                $successCount++;
            }
        }

        header('Location: ' . URLROOT . '/admin/promotions?msg=Đã gửi mã giảm giá thành công cho ' . $successCount . ' người dùng!');
        exit();
    }

    public function reviews() {
        $search = $_GET['search'] ?? '';
        $reviews = $this->adminModel->getAllReviews($search);
        
        $data = [
            'title' => 'Quản lý Đánh giá - TechExpert',
            'reviews' => $reviews,
            'search' => $search
        ];
        
        $this->view('admin/reviews', $data);
    }

    public function deleteReview($id) {
        if ($this->adminModel->deleteReview($id)) {
            header('Location: ' . URLROOT . '/admin/reviews?msg=Xóa đánh giá thành công');
        } else {
            header('Location: ' . URLROOT . '/admin/reviews?error=Không thể xóa đánh giá');
        }
        exit();
    }

    public function replyReview($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reply = trim($_POST['reply']);
            
            if (!empty($reply)) {
                if ($this->adminModel->replyReview($id, $reply)) {
                    header('Location: ' . URLROOT . '/admin/reviews?msg=Phản hồi đánh giá thành công');
                    exit();
                }
            }
            header('Location: ' . URLROOT . '/admin/reviews?error=Nội dung phản hồi không được để trống');
            exit();
        }
    }

    public function feedbacks() {
        $search = $_GET['search'] ?? '';
        $feedbacks = $this->adminModel->getAllFeedbacks($search);
        
        $data = [
            'title' => 'Quản lý Góp ý - TechExpert',
            'feedbacks' => $feedbacks,
            'search' => $search
        ];
        
        $this->view('admin/feedbacks', $data);
    }

    public function deleteFeedback($id) {
        if ($this->adminModel->deleteFeedback($id)) {
            header('Location: ' . URLROOT . '/admin/feedbacks?msg=Xóa góp ý thành công');
        } else {
            header('Location: ' . URLROOT . '/admin/feedbacks?error=Không thể xóa góp ý');
        }
        exit();
    }

    public function updateFeedbackStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = intval($_POST['status'] ?? 0);
            if ($this->adminModel->updateFeedbackStatus($id, $status)) {
                header('Location: ' . URLROOT . '/admin/feedbacks?msg=Cập nhật trạng thái góp ý thành công');
            } else {
                header('Location: ' . URLROOT . '/admin/feedbacks?error=Không thể cập nhật trạng thái');
            }
            exit();
        }
    }

    public function chatbot() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $faqs = $this->chatbotModel->getChatbotDataPaginated($search, $limit, $offset);
        $total = $this->chatbotModel->getTotalChatbotDataCount($search);
        $totalPages = ceil($total / $limit);

        $data = [
            'title' => 'Quản lý Chatbot',
            'faqs' => $faqs,
            'search' => $search,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'limit' => $limit
            ]
        ];
        $this->view('admin/chatbot/index', $data);
    }

    public function addChatbotData() {
        $data = [
            'title' => 'Thêm dữ liệu Chatbot'
        ];
        $this->view('admin/chatbot/add', $data);
    }

    public function storeChatbotData() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'question' => trim($_POST['question']),
                'answer' => trim($_POST['answer']),
                'keywords' => trim($_POST['keywords'])
            ];

            if ($this->chatbotModel->addData($data)) {
                header('Location: ' . URLROOT . '/admin/chatbot?success=added');
            } else {
                header('Location: ' . URLROOT . '/admin/chatbot?error=failed');
            }
        }
    }

    public function editChatbotData($id) {
        $faq = $this->chatbotModel->getDataById($id);
        if (!$faq) {
            header('Location: ' . URLROOT . '/admin/chatbot');
            exit();
        }

        $data = [
            'title' => 'Sửa dữ liệu Chatbot',
            'faq' => $faq
        ];
        $this->view('admin/chatbot/edit', $data);
    }

    public function updateChatbotData($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'question' => trim($_POST['question']),
                'answer' => trim($_POST['answer']),
                'keywords' => trim($_POST['keywords'])
            ];

            if ($this->chatbotModel->updateData($id, $data)) {
                header('Location: ' . URLROOT . '/admin/chatbot?success=updated');
            } else {
                header('Location: ' . URLROOT . '/admin/chatbot?error=failed');
            }
        }
    }

    public function deleteChatbotData($id) {
        if ($this->chatbotModel->deleteData($id)) {
            header('Location: ' . URLROOT . '/admin/chatbot?success=deleted');
        } else {
            header('Location: ' . URLROOT . '/admin/chatbot?error=failed');
        }
    }

    public function chatbotHistory() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $history = $this->chatbotModel->getChatHistoryPaginated($search, $limit, $offset);
        $total = $this->chatbotModel->getTotalChatHistoryCount($search);
        $totalPages = ceil($total / $limit);

        $data = [
            'title' => 'Lịch sử trò chuyện',
            'history' => $history,
            'search' => $search,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'limit' => $limit
            ]
        ];
        $this->view('admin/chatbot/history', $data);
    }

    // --- Brand Management ---
    
    public function brands() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        
        $brands = $this->adminModel->getAdminBrandsPaginated($search, $limit, $offset);
        $totalBrands = $this->adminModel->getBrandsCount($search);
        $totalPages = ceil($totalBrands / $limit);
        
        $data = [
            'title' => 'Quản lý thương hiệu - TechExpert',
            'brands' => $brands,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalBrands,
                'limit' => $limit,
                'start_record' => ($totalBrands > 0) ? $offset + 1 : 0,
                'end_record' => min($offset + $limit, $totalBrands)
            ],
            'filters' => [
                'search' => $search
            ]
        ];
        $this->view('admin/brands', $data);
    }

    public function addBrand() {
        $data = [
            'title' => 'Thêm thương hiệu mới',
            'name' => '',
            'description' => '',
            'errors' => []
        ];
        $this->view('admin/add_brand', $data);
    }

    public function storeBrand() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'logo' => '',
                'errors' => []
            ];

            if (empty($data['name'])) $data['errors']['name'] = 'Vui lòng nhập tên thương hiệu';

            // Logo handling
            if (!empty($_FILES['logo']['name'])) {
                $uploadDir = ROOT . '/public/img/brands/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $fileName)) {
                    $data['logo'] = URLROOT . '/img/brands/' . $fileName;
                }
            } elseif (!empty($_POST['logo_library'])) {
                $data['logo'] = trim($_POST['logo_library']);
            }

            if (empty($data['errors'])) {
                if ($this->adminModel->addBrand($data)) {
                    header('Location: ' . URLROOT . '/admin/brands?msg=Thêm thương hiệu thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi lưu dữ liệu';
                }
            }
            $this->view('admin/add_brand', $data);
        }
    }

    public function editBrand($id) {
        $brand = $this->adminModel->getBrandById($id);
        if (!$brand) {
            header('Location: ' . URLROOT . '/admin/brands');
            exit();
        }

        $data = [
            'title' => 'Chỉnh sửa thương hiệu',
            'brand' => $brand,
            'errors' => []
        ];
        $this->view('admin/edit_brand', $data);
    }

    public function updateBrand($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'logo' => '',
                'errors' => []
            ];

            if (empty($data['name'])) $data['errors']['name'] = 'Vui lòng nhập tên thương hiệu';

            // Logo handling
            if (!empty($_FILES['logo']['name'])) {
                $uploadDir = ROOT . '/public/img/brands/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $fileName)) {
                    $data['logo'] = URLROOT . '/img/brands/' . $fileName;
                }
            } elseif (!empty($_POST['logo_library'])) {
                $data['logo'] = trim($_POST['logo_library']);
            }

            if (empty($data['errors'])) {
                if ($this->adminModel->updateBrand($data)) {
                    header('Location: ' . URLROOT . '/admin/brands?msg=Cập nhật thương hiệu thành công');
                    exit();
                } else {
                    $data['errors']['general'] = 'Có lỗi xảy ra khi cập nhật';
                }
            }
            $data['brand'] = $this->adminModel->getBrandById($id);
            $this->view('admin/edit_brand', $data);
        }
    }

    public function deleteBrand($id) {
        if ($this->adminModel->deleteBrand($id)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Xóa thương hiệu thành công']);
                exit();
            }
            header('Location: ' . URLROOT . '/admin/brands?msg=Xóa thương hiệu thành công');
            exit();
        } else {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Không thể xóa thương hiệu']);
                exit();
            }
            header('Location: ' . URLROOT . '/admin/brands?error=Không thể xóa thương hiệu');
            exit();
        }
    }

    public function settings() {
        $settings = $this->adminModel->getSettings();
        // Convert to key => value array for easier use in view
        $formattedSettings = [];
        foreach ($settings as $s) {
            $formattedSettings[$s['key']] = $s['value'];
        }

        $data = [
            'title' => 'Cài đặt hệ thống - Admin TechExpert',
            'settings' => $formattedSettings
        ];
        $this->view('admin/settings', $data);
    }

    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle logo upload if provided
            if (isset($_FILES['store_logo']) && $_FILES['store_logo']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
                $filename = $_FILES['store_logo']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $uploadDir = ROOT . '/public/images/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    $newFilename = 'logo.' . $ext;
                    if (move_uploaded_file($_FILES['store_logo']['tmp_name'], $uploadDir . $newFilename)) {
                        $logoUrl = URLROOT . '/public/images/' . $newFilename;
                        $this->adminModel->updateSetting('store_logo', $logoUrl);
                    }
                }
            }

            // Update other settings
            foreach ($_POST as $key => $value) {
                if ($key !== 'store_logo') {
                    $this->adminModel->updateSetting($key, trim($value));
                }
            }

            $_SESSION['admin_success'] = "Cập nhật cài đặt hệ thống thành công!";
            header('Location: ' . URLROOT . '/admin/settings');
            exit();
        }
    }

    public function transactions() {
        $transactions = $this->adminModel->getBankTransactions(100);
        
        $data = [
            'title' => 'Lịch sử giao dịch ngân hàng',
            'transactions' => $transactions
        ];

        $this->view('admin/transactions', $data);
    }

    public function media() {
        $categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $baseDir = ROOT . '/public/img/';
        $folders = ['products', 'brands', 'reviews'];
        $files = [];
        
        $usedImages = $this->adminModel->getAllUsedImages();
        
        foreach ($folders as $folder) {
            if ($categoryFilter !== 'all' && $categoryFilter !== $folder) {
                continue;
            }
            
            $dirPath = $baseDir . $folder . '/';
            if (is_dir($dirPath)) {
                $dirFiles = scandir($dirPath);
                foreach ($dirFiles as $file) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    
                    // Simple search check
                    if (!empty($search) && stripos($file, $search) === false) {
                        continue;
                    }
                    
                    $fullPath = $dirPath . $file;
                    if (is_file($fullPath)) {
                        $size = filesize($fullPath);
                        $mtime = filemtime($fullPath);
                        
                        // Construct URLs
                        $url1 = URLROOT . '/img/' . $folder . '/' . $file;
                        $url2 = URLROOT . '/public/img/' . $folder . '/' . $file;
                        
                        $inUse = false;
                        $usages = [];
                        
                        if (isset($usedImages[$url1])) {
                            $inUse = true;
                            $usages = array_merge($usages, $usedImages[$url1]);
                        }
                        if (isset($usedImages[$url2])) {
                            $inUse = true;
                            $usages = array_merge($usages, $usedImages[$url2]);
                        }
                        
                        $files[] = [
                            'name' => $file,
                            'folder' => $folder,
                            'size' => $size,
                            'formatted_size' => $this->formatBytes($size),
                            'mtime' => $mtime,
                            'formatted_date' => date('d/m/Y H:i', $mtime),
                            'url' => $url1, // default preview URL
                            'url_alt' => $url2,
                            'in_use' => $inUse,
                            'usages' => $usages
                        ];
                    }
                }
            }
        }
        
        // Sort files by mtime DESC (newest first)
        usort($files, function($a, $b) {
            return $b['mtime'] - $a['mtime'];
        });
        
        $data = [
            'title' => 'Quản lý thư viện ảnh - TechExpert',
            'files' => $files,
            'category' => $categoryFilter,
            'search' => $search
        ];

        if (isset($_GET['json']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'files' => $files
            ]);
            exit();
        }
        
        $this->view('admin/media', $data);
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function uploadMedia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }
        
        $folder = isset($_POST['folder']) ? trim($_POST['folder']) : 'products';
        if (!in_array($folder, ['products', 'brands', 'reviews'])) {
            $folder = 'products';
        }
        
        $uploadDir = ROOT . '/public/img/' . $folder . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $uploadedFiles = [];
        $errors = [];
        
        // Handle single or multiple file uploads
        if (!empty($_FILES['files'])) {
            $filesInput = $_FILES['files'];
            
            // Check if it's multiple files or a single file
            $fileCount = is_array($filesInput['name']) ? count($filesInput['name']) : 1;
            
            for ($i = 0; $i < $fileCount; $i++) {
                $name = is_array($filesInput['name']) ? $filesInput['name'][$i] : $filesInput['name'];
                $tmpName = is_array($filesInput['tmp_name']) ? $filesInput['tmp_name'][$i] : $filesInput['tmp_name'];
                $error = is_array($filesInput['error']) ? $filesInput['error'][$i] : $filesInput['error'];
                $size = is_array($filesInput['size']) ? $filesInput['size'][$i] : $filesInput['size'];
                
                if ($error !== UPLOAD_ERR_OK) {
                    $errors[] = "Lỗi khi tải tệp $name";
                    continue;
                }
                
                // Validate file extension
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $errors[] = "Định dạng tệp $name không được hỗ trợ (chỉ chấp nhận JPG, JPEG, PNG, GIF, WEBP, SVG)";
                    continue;
                }
                
                // Validate size (limit to 5MB per file)
                if ($size > 5 * 1024 * 1024) {
                    $errors[] = "Tệp $name vượt quá kích thước tối đa cho phép (5MB)";
                    continue;
                }
                
                // Unique filename
                $cleanName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', pathinfo($name, PATHINFO_FILENAME));
                $newFilename = time() . '_' . $cleanName . '.' . $ext;
                
                if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                    $url = URLROOT . '/img/' . $folder . '/' . $newFilename;
                    $uploadedFiles[] = [
                        'name' => $newFilename,
                        'url' => $url,
                        'size' => $size,
                        'formatted_size' => $this->formatBytes($size)
                    ];
                } else {
                    $errors[] = "Không thể lưu tệp $name";
                }
            }
        } else {
            $errors[] = "Không tìm thấy tệp tải lên";
        }
        
        header('Content-Type: application/json');
        if (empty($errors)) {
            echo json_encode([
                'success' => true,
                'message' => 'Tải tệp lên thành công',
                'files' => $uploadedFiles
            ]);
        } else {
            echo json_encode([
                'success' => count($uploadedFiles) > 0,
                'message' => implode('. ', $errors),
                'files' => $uploadedFiles,
                'errors' => $errors
            ]);
        }
        exit();
    }

    public function deleteMedia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }
        
        $folder = isset($_POST['folder']) ? trim($_POST['folder']) : '';
        $filename = isset($_POST['filename']) ? trim($_POST['filename']) : '';
        
        if (empty($folder) || empty($filename) || !in_array($folder, ['products', 'brands', 'reviews'])) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
            exit();
        }
        
        // Safety checks to prevent path traversal
        $filename = basename($filename);
        $fullPath = ROOT . '/public/img/' . $folder . '/' . $filename;
        
        if (!file_exists($fullPath)) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy tệp tin trên hệ thống']);
            exit();
        }
        
        // Check database usage
        $usedImages = $this->adminModel->getAllUsedImages();
        $url1 = URLROOT . '/img/' . $folder . '/' . $filename;
        $url2 = URLROOT . '/public/img/' . $folder . '/' . $filename;
        
        $inUse = false;
        $usages = [];
        if (isset($usedImages[$url1])) {
            $inUse = true;
            $usages = array_merge($usages, $usedImages[$url1]);
        }
        if (isset($usedImages[$url2])) {
            $inUse = true;
            $usages = array_merge($usages, $usedImages[$url2]);
        }
        
        if ($inUse) {
            $detailList = [];
            foreach ($usages as $usage) {
                $detailList[] = $usage['detail'];
            }
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Không thể xóa ảnh này vì đang được sử dụng ở các mục sau: ' . implode(', ', $detailList),
                'usages' => $usages
            ]);
            exit();
        }
        
        // Safe to delete
        if (unlink($fullPath)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Xóa ảnh thành công']);
        } else {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Không thể xóa tệp tin khỏi ổ đĩa']);
        }
        exit();
    }

    public function exportStats() {
        $type = $_GET['type'] ?? 'all';
        $revenue = $this->adminModel->getTotalRevenue();
        $ordersCount = $this->adminModel->getTotalOrders();
        $inventoryCount = $this->adminModel->getTotalProducts();
        $usersCount = $this->adminModel->getTotalUsers();
        
        $revenueByBrand = $this->adminModel->getRevenueByBrand();
        $revenueByWeek = $this->adminModel->getRevenueByWeek(8);
        $revenueByMonth = $this->adminModel->getRevenueByMonth();
        $revenueByYear = $this->adminModel->getRevenueByYear();
        
        $topProducts = $this->adminModel->getTopSellingProducts(50);
        $topCustomers = $this->adminModel->getTopCustomers(50);
        $lowStockProducts = $this->adminModel->getLowStockProducts(50);
        
        // Thiết lập header xuất file Excel chuyên nghiệp .xls
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=bao_cao_thong_ke_" . date('Y-m-d_H-i') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Khởi dựng HTML cấu trúc Excel XML hỗ trợ hiển thị lưới Gridlines và font chữ đẹp mắt
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head>';
        echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Báo cáo TechExpert</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '<style>';
        echo 'body { font-family: "Segoe UI", system-ui, -apple-system, sans-serif; color: #334155; margin: 20px; }';
        echo 'table { border-collapse: collapse; margin-bottom: 25px; width: 100%; }';
        echo 'th { background-color: #0f172a; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 14px; text-align: left; font-size: 13px; }';
        echo 'td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; font-size: 13px; color: #475569; }';
        echo '.text-right { text-align: right; }';
        echo '.text-center { text-align: center; }';
        echo '.bold { font-weight: bold; }';
        echo '.title { font-size: 22px; font-weight: bold; color: #0f172a; padding-bottom: 5px; }';
        echo '.subtitle { font-size: 12px; color: #64748b; padding-bottom: 15px; }';
        echo '.section-header { background-color: #f8fafc; color: #0f172a; font-size: 14px; font-weight: bold; border-left: 5px solid #3b82f6; padding: 10px 15px; margin-top: 15px; margin-bottom: 15px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; border-radius: 4px; }';
        echo '.kpi-table { width: 100%; border: none; margin-bottom: 30px; }';
        echo '.kpi-card { background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 18px; text-align: center; }';
        echo '.kpi-title { font-size: 11px; color: #0369a1; text-transform: uppercase; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.5px; }';
        echo '.kpi-value { font-size: 20px; font-weight: bold; color: #0284c7; }';
        echo '.zebra-row { background-color: #f8fafc; }';
        echo '.alert-stock { color: #991b1b; font-weight: bold; background-color: #fef2f2; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        // Tiêu đề lớn
        echo '<table style="border:none; margin-bottom:10px;">';
        echo '<tr><td style="border:none; padding:0;" colspan="4"><div class="title" style="font-size: 22px; font-weight: bold; color: #0f172a;">BÁO CÁO THỐNG KÊ DOANH THU CHI TIẾT</div></td></tr>';
        echo '<tr><td style="border:none; padding:0; padding-bottom:15px;" colspan="4"><div class="subtitle" style="font-size: 12px; color: #64748b;">Hệ thống bán hàng TechExpert | Ngày lập báo cáo: ' . date('d/m/Y H:i:s') . '</div></td></tr>';
        echo '</table>';
        
        // I. KPI Cards
        echo '<div class="section-header" style="background-color: #f8fafc; color: #0f172a; font-size: 14px; font-weight: bold; border-left: 5px solid #3b82f6; padding: 10px 15px; margin-top: 15px; margin-bottom: 15px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">I. DOANH THU & CHỈ SỐ KPI CHÍNH</div>';
        echo '<table class="kpi-table" style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
        echo '<colgroup>';
        echo '<col style="width: 25%;">';
        echo '<col style="width: 25%;">';
        echo '<col style="width: 25%;">';
        echo '<col style="width: 25%;">';
        echo '</colgroup>';
        echo '<tr>';
        echo '<td align="center" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 18px; text-align: center; width: 25%;"><div class="kpi-title" style="font-size: 11px; color: #0369a1; text-transform: uppercase; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.5px;">Tổng doanh thu</div><div class="kpi-value" style="font-size: 20px; font-weight: bold; color: #0284c7;">' . number_format($revenue, 0, ',', '.') . ' ₫</div></td>';
        echo '<td align="center" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 18px; text-align: center; width: 25%;"><div class="kpi-title" style="font-size: 11px; color: #0369a1; text-transform: uppercase; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.5px;">Tổng đơn hàng</div><div class="kpi-value" style="font-size: 20px; font-weight: bold; color: #0284c7;">' . number_format($ordersCount, 0, ',', '.') . '</div></td>';
        echo '<td align="center" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 18px; text-align: center; width: 25%;"><div class="kpi-title" style="font-size: 11px; color: #0369a1; text-transform: uppercase; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.5px;">Tổng sản phẩm</div><div class="kpi-value" style="font-size: 20px; font-weight: bold; color: #0284c7;">' . number_format($inventoryCount, 0, ',', '.') . '</div></td>';
        echo '<td align="center" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 18px; text-align: center; width: 25%;"><div class="kpi-title" style="font-size: 11px; color: #0369a1; text-transform: uppercase; font-weight: bold; margin-bottom: 6px; letter-spacing: 0.5px;">Tổng khách hàng</div><div class="kpi-value" style="font-size: 20px; font-weight: bold; color: #0284c7;">' . number_format($usersCount, 0, ',', '.') . '</div></td>';
        echo '</tr>';
        echo '</table>';
        
        // II. Phân tích doanh thu theo hãng
        if ($type === 'all' || $type === 'brand') {
            echo '<div class="section-header">II. PHÂN TÍCH DOANH THU THEO HÃNG</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 60%;">';
            echo '<col style="width: 40%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="left" style="width: 60%; text-align: left;">Tên hãng sản xuất</th>';
            echo '<th align="right" style="width: 40%; text-align: right;">Doanh thu đạt được</th>';
            echo '</tr>';
            $zebra = false;
            foreach ($revenueByBrand as $brand) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="left" style="text-align: left;">' . htmlspecialchars($brand['label']) . '</td>';
                echo '<td align="right" class="bold" style="text-align: right; color: #0453cd;">' . number_format($brand['value'], 0, ',', '.') . ' ₫</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        // III. Phân tích doanh thu theo Tuần
        if ($type === 'all' || $type === 'week') {
            echo '<div class="section-header">III. DOANH THU THEO TUẦN (8 tuần gần nhất)</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 60%;">';
            echo '<col style="width: 40%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="left" style="width: 60%; text-align: left;">Khoảng thời gian</th>';
            echo '<th align="right" style="width: 40%; text-align: right;">Doanh thu</th>';
            echo '</tr>';
            $zebra = false;
            foreach ($revenueByWeek as $week) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="left" style="text-align: left;">' . htmlspecialchars($week['label']) . '</td>';
                echo '<td align="right" class="bold" style="text-align: right;">' . number_format($week['revenue'], 0, ',', '.') . ' ₫</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        // IV. Phân tích doanh thu theo Tháng
        if ($type === 'all' || $type === 'month') {
            echo '<div class="section-header">IV. DOANH THU THEO THÁNG</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 60%;">';
            echo '<col style="width: 40%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="left" style="width: 60%; text-align: left;">Khoảng thời gian</th>';
            echo '<th align="right" style="width: 40%; text-align: right;">Doanh thu</th>';
            echo '</tr>';
            $zebra = false;
            foreach ($revenueByMonth as $month) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="left" style="text-align: left;">Tháng ' . htmlspecialchars($month['month']) . '</td>';
                echo '<td align="right" class="bold" style="text-align: right;">' . number_format($month['revenue'], 0, ',', '.') . ' ₫</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        // V. Phân tích doanh thu theo Năm
        if ($type === 'all' || $type === 'year') {
            echo '<div class="section-header">V. DOANH THU THEO NĂM</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 60%;">';
            echo '<col style="width: 40%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="left" style="width: 60%; text-align: left;">Khoảng thời gian</th>';
            echo '<th align="right" style="width: 40%; text-align: right;">Doanh thu</th>';
            echo '</tr>';
            $zebra = false;
            foreach ($revenueByYear as $year) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="left" style="text-align: left;">Năm ' . htmlspecialchars($year['year']) . '</td>';
                echo '<td align="right" class="bold" style="text-align: right;">' . number_format($year['revenue'], 0, ',', '.') . ' ₫</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        // VI. Top Selling Products
        if ($type === 'all' || $type === 'product') {
            echo '<div class="section-header">VI. TOP 50 SẢN PHẨM BÁN CHẠY NHẤT</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 15%;">';
            echo '<col style="width: 50%;">';
            echo '<col style="width: 20%;">';
            echo '<col style="width: 15%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="center" style="width: 15%; text-align: center;">Mã sản phẩm</th>';
            echo '<th align="left" style="width: 50%; text-align: left;">Tên sản phẩm</th>';
            echo '<th align="right" style="width: 20%; text-align: right;">Đơn giá</th>';
            echo '<th align="center" style="width: 15%; text-align: center;">Số lượng bán ra</th>';
            echo '</tr>';
            
            $zebra = false;
            foreach ($topProducts as $prod) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="center" style="text-align: center;">' . htmlspecialchars($prod['id']) . '</td>';
                echo '<td align="left" class="bold" style="color: #111827; text-align: left;">' . htmlspecialchars($prod['name']) . '</td>';
                echo '<td align="right" style="text-align: right;">' . number_format($prod['price'], 0, ',', '.') . ' ₫</td>';
                echo '<td align="center" class="bold" style="text-align: center; color: #0453cd;">' . htmlspecialchars($prod['sold_count']) . '</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        // VII. Top Customers
        if ($type === 'all' || $type === 'customer') {
            echo '<div class="section-header">VII. TOP 50 KHÁCH HÀNG TIÊU BIỂU</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 25%;">';
            echo '<col style="width: 40%;">';
            echo '<col style="width: 15%;">';
            echo '<col style="width: 20%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="left" style="width: 25%; text-align: left;">Họ tên khách hàng</th>';
            echo '<th align="left" style="width: 40%; text-align: left;">Địa chỉ Email</th>';
            echo '<th align="center" style="width: 15%; text-align: center;">Số đơn thành công</th>';
            echo '<th align="center" style="width: 20%; text-align: center;">Tổng chi tiêu</th>';
            echo '</tr>';
            
            $zebra = false;
            foreach ($topCustomers as $cust) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="left" class="bold" style="text-align: left;">' . htmlspecialchars($cust['name']) . '</td>';
                echo '<td align="left" style="text-align: left;">' . htmlspecialchars($cust['email']) . '</td>';
                echo '<td align="center" style="text-align: center;">' . htmlspecialchars($cust['order_count']) . '</td>';
                echo '<td align="center" class="bold" style="text-align: center; color: #059669;">' . number_format($cust['total_spent'], 0, ',', '.') . ' ₫</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        // VIII. Low Stock
        if ($type === 'all' || $type === 'low_stock') {
            echo '<div class="section-header">VIII. SẢN PHẨM SẮP HẾT HÀNG (Tồn kho dưới 5)</div>';
            echo '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">';
            echo '<colgroup>';
            echo '<col style="width: 15%;">';
            echo '<col style="width: 50%;">';
            echo '<col style="width: 20%;">';
            echo '<col style="width: 15%;">';
            echo '</colgroup>';
            echo '<tr>';
            echo '<th align="center" style="width: 15%; text-align: center;">Mã sản phẩm</th>';
            echo '<th align="left" style="width: 50%; text-align: left;">Tên sản phẩm</th>';
            echo '<th align="right" style="width: 20%; text-align: right;">Đơn giá</th>';
            echo '<th align="center" style="width: 15%; text-align: center;">Số lượng tồn kho</th>';
            echo '</tr>';
            
            $zebra = false;
            foreach ($lowStockProducts as $low) {
                $class = $zebra ? ' class="zebra-row"' : '';
                echo '<tr' . $class . '>';
                echo '<td align="center" style="text-align: center;">' . htmlspecialchars($low['id']) . '</td>';
                echo '<td align="left" style="color: #374151; text-align: left;">' . htmlspecialchars($low['name']) . '</td>';
                echo '<td align="right" style="text-align: right;">' . number_format($low['price'], 0, ',', '.') . ' ₫</td>';
                echo '<td align="center" class="alert-stock" style="text-align: center;">' . htmlspecialchars($low['stock']) . ' sản phẩm</td>';
                echo '</tr>';
                $zebra = !$zebra;
            }
            echo '</table>';
        }
        
        echo '</body>';
        echo '</html>';
        exit();
    }

    public function livechat() {
        $data = [
            'title' => 'Hỗ trợ trực tuyến'
        ];
        $this->view('admin/chatbot/live', $data);
    }

    public function getLiveChatSessions() {
        ob_start();
        $db = new Database();
        
        $sql = "SELECT s.*, c.full_name as customer_name, u.email as customer_email,
                (SELECT question FROM chat_history 
                 WHERE customer_id = s.customer_id AND question LIKE '[SHOP]%' 
                 ORDER BY chatted_at DESC LIMIT 1) as last_customer_message,
                (SELECT answer FROM chat_history 
                 WHERE customer_id = s.customer_id AND answer LIKE '[SHOP]%' 
                 ORDER BY chatted_at DESC LIMIT 1) as last_admin_message,
                (SELECT chatted_at FROM chat_history 
                 WHERE customer_id = s.customer_id AND (question LIKE '[SHOP]%' OR answer LIKE '[SHOP]%') 
                 ORDER BY chatted_at DESC LIMIT 1) as last_message_time
                FROM support_sessions s
                JOIN users u ON s.customer_id = u.id
                LEFT JOIN customers c ON s.customer_id = c.user_id
                ORDER BY CASE WHEN s.status = 'pending' THEN 1 WHEN s.status = 'active' THEN 2 ELSE 3 END, s.updated_at DESC";
                
        $db->query($sql);
        $sessions = $db->resultSet();
        
        $formatted = [];
        foreach ($sessions as $s) {
            $lastMsg = '';
            $lastTime = $s['updated_at'];
            if ($s['last_message_time']) {
                $lastTime = $s['last_message_time'];
                
                // Get the actual last message in chat_history for this customer that starts with [SHOP]
                $db->query("SELECT question, answer, chatted_at FROM chat_history 
                            WHERE customer_id = :cid AND (question LIKE '[SHOP]%' OR answer LIKE '[SHOP]%') 
                            ORDER BY chatted_at DESC LIMIT 1");
                $db->bind(':cid', $s['customer_id']);
                $last = $db->single();
                if ($last) {
                    if ($last['question'] !== null) {
                        $lastMsg = "Khách: " . substr($last['question'], 7);
                    } else {
                        $lastMsg = "Bạn: " . substr($last['answer'], 7);
                    }
                    $lastTime = $last['chatted_at'];
                }
            } else {
                $lastMsg = 'Chưa có tin nhắn';
            }
            
            $formatted[] = [
                'customer_id' => $s['customer_id'],
                'customer_name' => $s['customer_name'] ?? $s['customer_email'],
                'status' => $s['status'],
                'admin_id' => $s['admin_id'],
                'last_message' => $lastMsg,
                'time' => date('H:i d/m', strtotime($lastTime))
            ];
        }
        
        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'sessions' => $formatted], JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getLiveChatMessages($customerId = null) {
        ob_start();
        if (!$customerId) {
            $customerId = $_GET['customer_id'] ?? null;
        }
        
        if (!$customerId) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Missing customer_id']);
            exit();
        }
        
        $db = new Database();
        
        // $customerId here is users.id (from support_sessions.customer_id)
        // Get customer name AND customers.id for chat_history FK
        $db->query("SELECT c.full_name, u.email, c.id as cust_table_id FROM users u LEFT JOIN customers c ON u.id = c.user_id WHERE u.id = :cid");
        $db->bind(':cid', $customerId);
        $cust = $db->single();
        $custName = $cust ? ($cust['full_name'] ?? $cust['email']) : 'Khách hàng';
        $custTableId = $cust ? $cust['cust_table_id'] : null; // customers.id for chat_history
        
        // Get support session info (uses users.id)
        $db->query("SELECT status, admin_id FROM support_sessions WHERE customer_id = :cid");
        $db->bind(':cid', $customerId);
        $session = $db->single();
        $status = $session ? $session['status'] : 'closed';
        $adminId = $session ? $session['admin_id'] : null;

        // Fetch messages starting with [SHOP] using customers.id for chat_history
        $messages = [];
        if ($custTableId) {
            $db->query("SELECT * FROM chat_history 
                        WHERE customer_id = :cid AND (question LIKE '[SHOP]%' OR answer LIKE '[SHOP]%') 
                        ORDER BY chatted_at ASC");
            $db->bind(':cid', $custTableId);
            $messages = $db->resultSet();
        }
        
        $formatted = [];
        foreach ($messages as $msg) {
            $q = $msg['question'];
            $a = $msg['answer'];
            $time = date('H:i', strtotime($msg['chatted_at']));
            
            if ($q !== null && strpos($q, '[SHOP] ') === 0) {
                $formatted[] = [
                    'sender' => 'user',
                    'message' => substr($q, 7),
                    'time' => $time
                ];
            }
            if ($a !== null && strpos($a, '[SHOP] ') === 0) {
                $formatted[] = [
                    'sender' => 'admin',
                    'message' => substr($a, 7),
                    'time' => $time
                ];
            }
        }
        
        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'customer_name' => $custName,
            'support_status' => $status,
            'admin_id' => $adminId,
            'current_admin_id' => $_SESSION['user_id'],
            'messages' => $formatted
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function sendLiveChatMessage() {
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['customer_id'] ?? null; // This is users.id from support_sessions
            $message = trim($_POST['message'] ?? '');
            
            if (!$userId || empty($message)) {
                while (ob_get_level() > 0) ob_end_clean();
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                exit();
            }
            
            try {
                $db = new Database();
                
                // Lookup customers.id from users.id for chat_history FK
                $db->query("SELECT id FROM customers WHERE user_id = :uid");
                $db->bind(':uid', $userId);
                $cust = $db->single();
                $custId = $cust ? $cust['id'] : null;
                
                // Check session status. If it's pending, auto accept it.
                $db->query("SELECT status, admin_id FROM support_sessions WHERE customer_id = :cid");
                $db->bind(':cid', $userId);
                $session = $db->single();
                
                $adminId = $_SESSION['user_id'];
                
                if (!$session) {
                    $db->query("INSERT INTO support_sessions (customer_id, status, admin_id, created_at, updated_at) VALUES (:cid, 'active', :admin_id, NOW(), NOW())");
                    $db->bind(':cid', $userId);
                    $db->bind(':admin_id', $adminId);
                    $db->execute();
                } else if ($session['status'] === 'pending') {
                    $db->query("UPDATE support_sessions SET status = 'active', admin_id = :admin_id, updated_at = NOW() WHERE customer_id = :cid");
                    $db->bind(':cid', $userId);
                    $db->bind(':admin_id', $adminId);
                    $db->execute();
                    
                    if ($custId) {
                        $db->query("INSERT INTO chat_history (customer_id, question, answer, chatted_at) VALUES (:cid, NULL, '[SHOP] Nhân viên hỗ trợ đã tham gia cuộc trò chuyện.', NOW())");
                        $db->bind(':cid', $custId);
                        $db->execute();
                    }
                }
                
                // Insert the admin's reply message using customers.id
                if ($custId) {
                    $db->query("INSERT INTO chat_history (customer_id, question, answer, chatted_at) VALUES (:cid, NULL, :answer, NOW())");
                    $db->bind(':cid', $custId);
                    $db->bind(':answer', '[SHOP] ' . $message);
                    $db->execute();
                }
                
                while (ob_get_level() > 0) ob_end_clean();
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'success', 'time' => date('H:i')]);
                exit();
            } catch (Exception $e) {
                while (ob_get_level() > 0) ob_end_clean();
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => 'DB error: ' . $e->getMessage()]);
                exit();
            }
        }
        
        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
        exit();
    }

    public function acceptSupportSession() {
        ob_start();
        $userId = $_POST['customer_id'] ?? null; // This is users.id from support_sessions
        $adminId = $_SESSION['user_id'];
        
        if (!$userId) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Missing customer_id']);
            exit();
        }
        
        try {
            $db = new Database();
            $db->query("UPDATE support_sessions SET status = 'active', admin_id = :admin_id, updated_at = NOW() WHERE customer_id = :cid");
            $db->bind(':cid', $userId);
            $db->bind(':admin_id', $adminId);
            $db->execute();
            
            // Lookup customers.id from users.id for chat_history FK
            $db->query("SELECT id FROM customers WHERE user_id = :uid");
            $db->bind(':uid', $userId);
            $cust = $db->single();
            
            if ($cust) {
                $custId = $cust['id'];
                $db->query("INSERT INTO chat_history (customer_id, question, answer, chatted_at) VALUES (:cid, NULL, '[SHOP] Nhân viên hỗ trợ đã tham gia cuộc trò chuyện.', NOW())");
                $db->bind(':cid', $custId);
                $db->execute();
            }
            
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'success']);
            exit();
        } catch (Exception $e) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'DB error: ' . $e->getMessage()]);
            exit();
        }
    }

    public function closeSupportSession() {
        ob_start();
        $userId = $_POST['customer_id'] ?? null; // This is users.id from support_sessions
        
        if (!$userId) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Missing customer_id']);
            exit();
        }
        
        try {
            $db = new Database();
            $db->query("UPDATE support_sessions SET status = 'closed', updated_at = NOW() WHERE customer_id = :cid");
            $db->bind(':cid', $userId);
            $db->execute();
            
            // Lookup customers.id from users.id for chat_history FK
            $db->query("SELECT id FROM customers WHERE user_id = :uid");
            $db->bind(':uid', $userId);
            $cust = $db->single();
            
            if ($cust) {
                $custId = $cust['id'];
                $db->query("INSERT INTO chat_history (customer_id, question, answer, chatted_at) VALUES (:cid, NULL, '[SHOP] Phiên hỗ trợ trực tuyến đã được đóng. Chatbot AI sẽ tiếp tục hỗ trợ bạn.', NOW())");
                $db->bind(':cid', $custId);
                $db->execute();
            }
            
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'success']);
            exit();
        } catch (Exception $e) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'DB error: ' . $e->getMessage()]);
            exit();
        }
    }

    public function orderPDF($id = null) {
        if (!$id) {
            header('Location: ' . URLROOT . '/admin/orders');
            exit();
        }
        $order = $this->adminModel->getOrderById($id);
        if (!$order) {
            header('Location: ' . URLROOT . '/admin/orders');
            exit();
        }
        $items = $this->adminModel->getOrderItems($id);

        $statusMapping = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'shipped' => 'Đã giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        $order['formatted_date'] = date('d/m/Y H:i', strtotime($order['ordered_at'] ?? 'now'));
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price_at_purchase'] * $item['quantity'];
        }
        $total = (float)($order['total_amount'] ?? 0);
        $order['formatted_total'] = number_format($total, 0, ',', '.');
        $order['formatted_subtotal'] = number_format($subtotal, 0, ',', '.');
        $status_key = strtolower($order['order_status'] ?? 'pending');
        $order['status_text'] = $statusMapping[$status_key] ?? 'Không rõ';

        // Render Invoice HTML for Dompdf
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Hoa don #' . $order['id'] . '</title>
            <style>
                body {
                    font-family: "DejaVu Sans", sans-serif;
                    font-size: 13px;
                    color: #333;
                    line-height: 1.5;
                }
                .invoice-header {
                    border-bottom: 2px solid #0453cd;
                    padding-bottom: 15px;
                    margin-bottom: 25px;
                }
                .logo-section {
                    float: left;
                    width: 50%;
                }
                .logo-text {
                    font-size: 24px;
                    font-weight: bold;
                    color: #0453cd;
                }
                .logo-subtext {
                    font-size: 10px;
                    color: #777;
                    text-transform: uppercase;
                }
                .meta-section {
                    float: right;
                    width: 50%;
                    text-align: right;
                }
                .invoice-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 5px;
                }
                .clear {
                    clear: both;
                }
                .info-table {
                    width: 100%;
                    margin-bottom: 25px;
                }
                .info-table td {
                    vertical-align: top;
                    padding: 4px 0;
                }
                .info-title {
                    font-weight: bold;
                    color: #0453cd;
                    font-size: 14px;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 5px;
                    margin-bottom: 10px;
                }
                .items-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 25px;
                }
                .items-table th {
                    background-color: #0453cd;
                    color: #fff;
                    font-weight: bold;
                    text-align: left;
                    padding: 8px 10px;
                    border: 1px solid #0453cd;
                }
                .items-table td {
                    padding: 8px 10px;
                    border: 1px solid #ddd;
                }
                .text-right {
                    text-align: right;
                }
                .text-center {
                    text-align: center;
                }
                .totals-section {
                    float: right;
                    width: 45%;
                }
                .totals-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .totals-table td {
                    padding: 6px 0;
                }
                .totals-table tr.total-row td {
                    border-top: 1px solid #333;
                    font-weight: bold;
                    font-size: 16px;
                    color: #ba1a1a;
                    padding-top: 10px;
                }
                .footer {
                    margin-top: 50px;
                    border-top: 1px solid #ddd;
                    padding-top: 15px;
                    text-align: center;
                    font-size: 11px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class="invoice-header">
                <div class="logo-section">
                    <div class="logo-text">TECHEXPERT</div>
                    <div class="logo-subtext">Precision Engineering</div>
                </div>
                <div class="meta-section">
                    <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>
                    <div>Mã đơn hàng: <strong>#' . $order['id'] . '</strong></div>
                    <div>Ngày đặt: ' . $order['formatted_date'] . '</div>
                </div>
                <div class="clear"></div>
            </div>

            <table class="info-table">
                <tr>
                    <td style="width: 50%;">
                        <div class="info-title">Thông tin khách hàng</div>
                        <div>Người nhận: <strong>' . htmlspecialchars($order['full_name']) . '</strong></div>
                        <div>Điện thoại: ' . htmlspecialchars($order['phone']) . '</div>
                        <div>Địa chỉ: ' . htmlspecialchars($order['shipping_address']) . '</div>
                    </td>
                    <td style="width: 50%; padding-left: 20px;">
                        <div class="info-title">Thông tin thanh toán</div>
                        <div>Phương thức: ' . htmlspecialchars($order['payment_method']) . '</div>
                        <div>Trạng thái thanh toán: ' . ($order['payment_status'] === 'Paid' ? 'Đã thanh toán' : 'Chờ thanh toán') . '</div>
                        <div>Trạng thái đơn hàng: ' . $order['status_text'] . '</div>
                    </td>
                </tr>
            </table>

            <div class="info-title">Chi tiết sản phẩm</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 8%;" class="text-center">STT</th>
                        <th>Tên sản phẩm / Linh kiện</th>
                        <th style="width: 15%;" class="text-right">Đơn giá</th>
                        <th style="width: 10%;" class="text-center">SL</th>
                        <th style="width: 20%;" class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>';
        
        $i = 1;
        foreach ($items as $item) {
            $itemTotal = $item['price_at_purchase'] * $item['quantity'];
            $html .= '
                    <tr>
                        <td class="text-center">' . $i++ . '</td>
                        <td>' . htmlspecialchars($item['name']) . '</td>
                        <td class="text-right">' . number_format($item['price_at_purchase'], 0, ',', '.') . ' đ</td>
                        <td class="text-center">' . $item['quantity'] . '</td>
                        <td class="text-right">' . number_format($itemTotal, 0, ',', '.') . ' đ</td>
                    </tr>';
        }

        $shippingFeeDisplay = $order['shipping_fee'] == 0 ? 'Miễn phí' : number_format($order['shipping_fee'], 0, ',', '.') . ' đ';
        $discountHtml = '';
        if ($order['discount_amount'] > 0) {
            $discountHtml = '
                <tr>
                    <td>Giảm giá:</td>
                    <td class="text-right">- ' . number_format($order['discount_amount'], 0, ',', '.') . ' đ</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td>Tạm tính:</td>
                        <td class="text-right">' . $order['formatted_subtotal'] . ' đ</td>
                    </tr>
                    <tr>
                        <td>Phí vận chuyển:</td>
                        <td class="text-right">' . $shippingFeeDisplay . '</td>
                    </tr>'
                    . $discountHtml .
                    '<tr class="total-row">
                        <td>Tổng thanh toán:</td>
                        <td class="text-right">' . $order['formatted_total'] . ' đ</td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>

            <div class="footer">
                Cảm ơn Quý khách đã mua sắm tại TechExpert!<br>
                Mọi thắc mắc xin liên hệ Hotline: 1900-8888 hoặc Email: support@techexpert.vn.
            </div>
        </body>
        </html>';

        // Generate PDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Invoice_TechExpert_' . $order['id'] . '.pdf', ['Attachment' => false]);
        exit();
    }

    public function dashboardPDF() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/admin');
            exit();
        }

        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $exportType = $_POST['export_type'] ?? 'all';
        $charts = $_POST['charts'] ?? [];

        $revenue_total = $this->adminModel->getTotalRevenue($startDate, $endDate);
        $orders_count = $this->adminModel->getTotalOrders($startDate, $endDate);
        $new_users = $this->adminModel->getNewUsersCount($startDate, $endDate);
        $inventory_count = $this->adminModel->getTotalProducts();
        $avg_order_value = $orders_count > 0 ? $revenue_total / $orders_count : 0;

        $topProducts = $this->adminModel->getTopSellingProducts(10, $startDate, $endDate);
        $topCustomers = $this->adminModel->getTopCustomers(10, $startDate, $endDate);
        $lowStockProducts = $this->adminModel->getLowStockProducts(10);

        $formattedStart = date('d/m/Y', strtotime($startDate));
        $formattedEnd = date('d/m/Y', strtotime($endDate));

        // Start HTML
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Bao cao thong ke</title>
            <style>
                body {
                    font-family: "DejaVu Sans", sans-serif;
                    font-size: 11px;
                    color: #333;
                    line-height: 1.4;
                }
                .header-section {
                    border-bottom: 2px solid #0453cd;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }
                .report-title {
                    font-size: 18px;
                    font-weight: bold;
                    color: #0453cd;
                    float: left;
                }
                .report-meta {
                    float: right;
                    text-align: right;
                    font-weight: bold;
                    color: #555;
                }
                .clear {
                    clear: both;
                }
                .kpi-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .kpi-card {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: center;
                    width: 20%;
                }
                .kpi-title {
                    font-size: 10px;
                    text-transform: uppercase;
                    color: #777;
                    margin-bottom: 5px;
                }
                .kpi-value {
                    font-size: 16px;
                    font-weight: bold;
                    color: #0453cd;
                }
                .charts-table {
                    width: 100%;
                    margin-bottom: 20px;
                }
                .chart-container {
                    border: 1px solid #ddd;
                    padding: 8px;
                    background-color: #fcfcfc;
                    text-align: center;
                }
                .chart-title {
                    font-weight: bold;
                    margin-bottom: 8px;
                    font-size: 11px;
                    color: #333;
                    text-align: left;
                }
                .chart-img {
                    max-width: 100%;
                    height: 140px;
                    object-fit: contain;
                }
                .grid-table {
                    width: 100%;
                    margin-bottom: 20px;
                }
                .grid-table td {
                    vertical-align: top;
                    width: 50%;
                }
                .section-title {
                    font-size: 12px;
                    font-weight: bold;
                    color: #0453cd;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 5px;
                    margin-bottom: 10px;
                }
                .data-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .data-table th {
                    background-color: #f5f5f5;
                    border: 1px solid #ddd;
                    padding: 6px 8px;
                    text-align: left;
                    font-weight: bold;
                }
                .data-table td {
                    border: 1px solid #ddd;
                    padding: 6px 8px;
                }
                .text-right {
                    text-align: right;
                }
                .text-center {
                    text-align: center;
                }
                .bold {
                    font-weight: bold;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>
        <body>
            <div class="header-section">
                <div class="report-title">BÁO CÁO THỐNG KÊ HOẠT ĐỘNG TECHEXPERT</div>
                <div class="report-meta">Chu kỳ: ' . $formattedStart . ' - ' . $formattedEnd . '</div>
                <div class="clear"></div>
            </div>

            <!-- KPIs Table -->
            <table class="kpi-table">
                <tr>
                    <td class="kpi-card">
                        <div class="kpi-title">Tổng doanh thu</div>
                        <div class="kpi-value">' . number_format($revenue_total, 0, ',', '.') . ' đ</div>
                    </td>
                    <td class="kpi-card">
                        <div class="kpi-title">Giá trị TB đơn</div>
                        <div class="kpi-value">' . number_format($avg_order_value, 0, ',', '.') . ' đ</div>
                    </td>
                    <td class="kpi-card">
                        <div class="kpi-title">Tổng đơn hàng</div>
                        <div class="kpi-value">' . number_format($orders_count) . ' đơn</div>
                    </td>
                    <td class="kpi-card">
                        <div class="kpi-title">Khách hàng mới</div>
                        <div class="kpi-value">' . number_format($new_users) . ' TV</div>
                    </td>
                    <td class="kpi-card">
                        <div class="kpi-title">Tổng sản phẩm</div>
                        <div class="kpi-value">' . number_format($inventory_count) . ' SP</div>
                    </td>
                </tr>
            </table>';

        // Display charts based on export type
        $showRevenueChart = in_array($exportType, ['all', 'month', 'year']);
        $showWeeklyChart = in_array($exportType, ['all', 'week']);
        $showCategoryChart = in_array($exportType, ['all', 'brand']);
        $showBrandChart = in_array($exportType, ['all', 'brand']);

        if ($showRevenueChart || $showWeeklyChart || $showCategoryChart || $showBrandChart) {
            $html .= '<div class="section-title">Biểu đồ thống kê</div>';
            $html .= '<table class="charts-table" cellspacing="10">';
            
            $rowCount = 0;
            if ($showRevenueChart && !empty($charts['revenue'])) {
                if ($rowCount % 2 == 0) $html .= '<tr>';
                $html .= '<td class="chart-container" style="width: 50%;">
                            <div class="chart-title">Doanh thu theo thời gian</div>
                            <img class="chart-img" src="' . $charts['revenue'] . '">
                          </td>';
                $rowCount++;
                if ($rowCount % 2 == 0) $html .= '</tr>';
            }
            if ($showWeeklyChart && !empty($charts['weekly'])) {
                if ($rowCount % 2 == 0) $html .= '<tr>';
                $html .= '<td class="chart-container" style="width: 50%;">
                            <div class="chart-title">Số lượng đơn hàng tuần</div>
                            <img class="chart-img" src="' . $charts['weekly'] . '">
                          </td>';
                $rowCount++;
                if ($rowCount % 2 == 0) $html .= '</tr>';
            }
            if ($showCategoryChart && !empty($charts['category'])) {
                if ($rowCount % 2 == 0) $html .= '<tr>';
                $html .= '<td class="chart-container" style="width: 50%;">
                            <div class="chart-title">Cấu trúc doanh thu theo danh mục</div>
                            <img class="chart-img" src="' . $charts['category'] . '">
                          </td>';
                $rowCount++;
                if ($rowCount % 2 == 0) $html .= '</tr>';
            }
            if ($showBrandChart && !empty($charts['brand'])) {
                if ($rowCount % 2 == 0) $html .= '<tr>';
                $html .= '<td class="chart-container" style="width: 50%;">
                            <div class="chart-title">Cấu trúc doanh thu theo thương hiệu</div>
                            <img class="chart-img" src="' . $charts['brand'] . '">
                          </td>';
                $rowCount++;
                if ($rowCount % 2 == 0) $html .= '</tr>';
            }
            if (!empty($charts['orderStatus'])) {
                if ($rowCount % 2 == 0) $html .= '<tr>';
                $html .= '<td class="chart-container" style="width: 50%;">
                            <div class="chart-title">Cấu trúc trạng thái đơn hàng</div>
                            <img class="chart-img" src="' . $charts['orderStatus'] . '">
                          </td>';
                $rowCount++;
                if ($rowCount % 2 == 0) $html .= '</tr>';
            }
            
            if ($rowCount % 2 != 0) {
                $html .= '<td style="width: 50%;"></td></tr>';
            }
            
            $html .= '</table>';
        }

        // Page break for lists
        $html .= '<div class="page-break"></div>';

        // Lists Grid
        $html .= '
            <table class="grid-table" cellspacing="15">
                <tr>';
        
        // Col 1: Top Products
        if (in_array($exportType, ['all', 'product'])) {
            $html .= '
                    <td>
                        <div class="section-title">Top sản phẩm bán chạy nhất</div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%;" class="text-center">STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th style="width: 25%;" class="text-right">Doanh thu</th>
                                    <th style="width: 20%;" class="text-center">Đã bán</th>
                                </tr>
                            </thead>
                            <tbody>';
            $idx = 1;
            foreach ($topProducts as $p) {
                $html .= '
                                <tr>
                                    <td class="text-center">' . $idx++ . '</td>
                                    <td class="bold">' . htmlspecialchars($p['name']) . '</td>
                                    <td class="text-right">' . number_format($p['sold_count'] * $p['price'], 0, ',', '.') . ' đ</td>
                                    <td class="text-center">' . $p['sold_count'] . '</td>
                                </tr>';
            }
            if (empty($topProducts)) {
                $html .= '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
            }
            $html .= '
                            </tbody>
                        </table>
                    </td>';
        }

        // Col 2: Top Customers
        if (in_array($exportType, ['all', 'customer'])) {
            $html .= '
                    <td>
                        <div class="section-title">Khách hàng tiêu biểu</div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%;" class="text-center">STT</th>
                                    <th>Khách hàng</th>
                                    <th style="width: 30%;" class="text-center">Tổng chi tiêu</th>
                                    <th style="width: 20%;" class="text-center">Số đơn</th>
                                </tr>
                            </thead>
                            <tbody>';
            $idx = 1;
            foreach ($topCustomers as $c) {
                $html .= '
                                <tr>
                                    <td class="text-center">' . $idx++ . '</td>
                                    <td class="bold">' . htmlspecialchars($c['name'] ?? 'Khách lẻ') . '</td>
                                    <td class="text-center">' . number_format($c['total_spent'], 0, ',', '.') . ' đ</td>
                                    <td class="text-center">' . $c['order_count'] . '</td>
                                </tr>';
            }
            if (empty($topCustomers)) {
                $html .= '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
            }
            $html .= '
                            </tbody>
                        </table>
                    </td>';
        }

        $html .= '
                </tr>
            </table>';

        // Low stock products row (if all or low stock)
        if (in_array($exportType, ['all', 'low_stock']) && !empty($lowStockProducts)) {
            $html .= '
            <div class="section-title">Danh sách sản phẩm sắp hết kho (Cần nhập hàng)</div>
            <table class="data-table" style="width: 50%;">
                <thead>
                    <tr>
                        <th style="width: 10%;" class="text-center">STT</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 25%;" class="text-center">Số lượng tồn</th>
                    </tr>
                </thead>
                <tbody>';
            $idx = 1;
            foreach ($lowStockProducts as $lp) {
                $html .= '
                    <tr>
                        <td class="text-center">' . $idx++ . '</td>
                        <td class="bold">' . htmlspecialchars($lp['name']) . '</td>
                        <td class="text-center bold" style="color: #ba1a1a;">' . $lp['stock'] . ' SP</td>
                    </tr>';
            }
            $html .= '
                </tbody>
            </table>';
        }

        $html .= '
        </body>
        </html>';

        // Generate PDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('TechExpert_Report_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        exit();
    }
}
