<?php

class OrderController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('OrderModel');
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        header('Location: ' . URLROOT . '/user/profile?tab=orders');
        exit();
    }

    public function detail($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        header('Location: ' . URLROOT . '/user/orderDetail/' . $id);
        exit();
    }

    public function cancel($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customerId = $_SESSION['customer_id'];
            
            if ($this->orderModel->cancelOrder($id, $customerId)) {
                // Return stock
                $items = $this->orderModel->getOrderItems($id);
                $productModel = $this->model('ProductModel');
                foreach ($items as $item) {
                    $productModel->increaseStock($item['product_id'], $item['quantity']);
                }
                
                header('Location: ' . URLROOT . '/user/orderDetail/' . $id . '?success=cancelled');
            } else {
                header('Location: ' . URLROOT . '/user/orderDetail/' . $id . '?error=cancel_failed');
            }
        } else {
            header('Location: ' . URLROOT . '/user/orderDetail/' . $id . '?error=not_post');
        }
        exit();
    }

    public function track() {
        $data = [
            'title' => __('track_title', 'Tra cứu đơn hàng') . ' | Lộ Trình & Trạng Thái Giao Hàng - TechExpert',
            'meta_description' => 'Tra cứu chi tiết trạng thái, tiến độ xử lý và lộ trình vận chuyển đơn hàng của bạn tại TechExpert. Nhập mã đơn hàng và số điện thoại để tra cứu.',
            'meta_keywords' => 'tra cuu don hang, theo doi don hang, kiem tra van don, techexpert',
            'order' => null,
            'items' => [],
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = trim($_POST['order_id'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if (empty($orderId) || empty($phone)) {
                $data['error'] = __('track_required_fields', 'Vui lòng nhập đầy đủ Mã đơn hàng và Số điện thoại.');
            } else {
                $order = $this->orderModel->getOrderByTracking($orderId, $phone);
                if ($order) {
                    $data['order'] = $order;
                    $data['items'] = $this->orderModel->getOrderItems($order['id']);
                    $data['logs'] = $this->orderModel->getOrderStatusLogs($order['id']);
                } else {
                    $data['error'] = __('track_not_found', 'Không tìm thấy đơn hàng phù hợp với thông tin đã cung cấp.');
                }
            }
        }

        $this->view('orders/track', $data);
    }
}
