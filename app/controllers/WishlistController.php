<?php

class WishlistController extends Controller {
    private $wishlistModel;

    public function __construct() {
        $this->wishlistModel = $this->model('WishlistModel');
    }

    public function toggle($productId) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thực hiện chức năng này', 'code' => 'unauthorized']);
            return;
        }

        $customerId = $_SESSION['customer_id'];
        
        if ($this->wishlistModel->isInWishlist($customerId, $productId)) {
            if ($this->wishlistModel->removeFromWishlist($customerId, $productId)) {
                echo json_encode(['status' => 'success', 'action' => 'removed', 'message' => 'Đã xóa khỏi danh sách yêu thích']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể xóa sản phẩm']);
            }
        } else {
            if ($this->wishlistModel->addToWishlist($customerId, $productId)) {
                echo json_encode(['status' => 'success', 'action' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể thêm sản phẩm']);
            }
        }
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $customerId = $_SESSION['customer_id'];
        $items = $this->wishlistModel->getWishlistByCustomer($customerId);
        
        $data = [
            'title' => 'Sản phẩm yêu thích',
            'items' => $items
        ];

        $this->view('wishlist/index', $data);
    }
}
