<?php

class HomeController extends Controller {
    public function index() {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $adminModel = new AdminModel(); // Using AdminModel for brand list if needed, or CategoryModel

        $wishlistModel = $this->model('WishlistModel');
        $wishlistIds = [];
        if (isset($_SESSION['customer_id'])) {
            $wishlistItems = $wishlistModel->getWishlistByCustomer($_SESSION['customer_id']);
            $wishlistIds = array_column($wishlistItems, 'id');
        }

        $recentlyViewedIds = $_SESSION['recently_viewed'] ?? [];
        $recentlyViewed = $productModel->getProductsByIds($recentlyViewedIds);

        $userModel = $this->model('UserModel');
        $savedVoucherIds = [];
        if (isset($_SESSION['user_id'])) {
            $savedVouchers = $userModel->getSavedVouchers($_SESSION['user_id']);
            $savedVoucherIds = array_column($savedVouchers, 'id');
        }

        $data = [
            'title' => 'Trang Chủ - TechExpert Store | Linh Kiện & PC Gaming Cao Cấp',
            'meta_description' => 'Hệ thống bán lẻ linh kiện máy tính, laptop, PC gaming, PC đồ họa cao cấp chính hãng tại TechExpert. Lắp ráp PC chuyên nghiệp, bảo hành uy tín, giao hàng nhanh chóng.',
            'meta_keywords' => 'pc gaming, pc do hoa, linh kien may tinh, laptop dell, build pc, lap rap may tinh, techexpert store',
            'products' => $productModel->getFeaturedProducts(8),
            'categories' => $categoryModel->getCategoriesWithBrands(),
            'new_arrivals' => $productModel->getNewArrivals(4),
            'best_sellers' => $productModel->getBestSellers(4),
            'brands' => $adminModel->getAllBrands(),
            'wishlist_ids' => $wishlistIds,
            'recently_viewed' => $recentlyViewed,
            'vouchers' => $adminModel->getAllVouchers(),
            'saved_voucher_ids' => $savedVoucherIds,
            'top_reviews' => $productModel->getTopReviews(3),
        ];
        $this->view('home/index', $data);
    }

    /**
     * Handle newsletter subscription POST request
     *
     * @return void
     */
    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT);
            exit();
        }

        header('Content-Type: application/json');

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'status' => 'error',
                'message' => __('newsletter_invalid_email', 'Email không hợp lệ. Vui lòng thử lại.')
            ]);
            exit();
        }

        $newsletterModel = $this->model('NewsletterModel');

        if ($newsletterModel->isSubscribed($email)) {
            echo json_encode([
                'status' => 'error',
                'message' => __('newsletter_already_subscribed', 'Email này đã đăng ký nhận bản tin trước đó.')
            ]);
            exit();
        }

        if ($newsletterModel->addSubscriber($email)) {
            echo json_encode([
                'status' => 'success',
                'message' => __('newsletter_success', 'Đăng ký nhận bản tin thành công!')
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => __('newsletter_error', 'Đã xảy ra lỗi. Vui lòng thử lại sau.')
            ]);
        }
        exit();
    }
}
