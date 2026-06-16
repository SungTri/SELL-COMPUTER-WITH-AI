<?php

class UserController extends Controller {
    private $userModel;
    private $orderModel;
    private $wishlistModel;
    private $addressModel;
    private $notificationModel;
    private $productModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
        $this->orderModel = $this->model('OrderModel');
        $this->wishlistModel = $this->model('WishlistModel');
        $this->addressModel = $this->model('AddressModel');
        $this->notificationModel = $this->model('NotificationModel');
        $this->productModel = $this->model('ProductModel');
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
    }

    public function profile() {
        $tab = $_GET['tab'] ?? 'profile';
        $userId = $_SESSION['user_id'];
        $customerId = $_SESSION['customer_id'];

        $user = $this->userModel->getUserProfile($userId);
        $orders = $this->orderModel->getOrdersByCustomer($customerId);
        $wishlist = $this->wishlistModel->getWishlistByCustomer($customerId);
        $addresses = $this->addressModel->getAddressesByCustomer($customerId);
        
        $statusMapping = [
            'pending' => __('order_status_pending', 'Chờ xử lý'),
            'processing' => __('order_status_processing', 'Đang xử lý'),
            'shipping' => __('order_status_shipping', 'Đang giao'),
            'shipped' => __('order_status_shipping', 'Đang giao'),
            'delivered' => __('order_status_completed', 'Đã giao'),
            'completed' => __('order_status_completed', 'Hoàn thành'),
            'cancelled' => __('order_status_cancelled', 'Đã hủy')
        ];

        foreach ($orders as &$order) {
            $order['status_text'] = $statusMapping[strtolower($order['status'])] ?? 'Không rõ';
        }

        // Notification pagination (10 per page)
        $notiPerPage = 10;
        $notiPage = max(1, intval($_GET['noti_page'] ?? 1));
        $notiOffset = ($notiPage - 1) * $notiPerPage;
        $totalNotiCount = $this->notificationModel->getTotalNotificationCount($userId);
        $totalNotiPages = max(1, ceil($totalNotiCount / $notiPerPage));
        $notiPage = min($notiPage, $totalNotiPages);
        $notiOffset = ($notiPage - 1) * $notiPerPage;

        $data = [
            'title' => 'Trang cá nhân',
            'noindex' => true,
            'tab' => $tab,
            'user' => [
                'full_name' => $user['full_name'] ?? 'Guest',
                'email' => $user['email'] ?? '',
                'phone' => $user['phone'] ?? '',
                'address' => $user['address'] ?? '',
                'gender' => $user['gender'] ?? 'Khác',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name'] ?? 'User') . '&background=0453cd&color=fff'
            ],
            'orders' => $orders,
            'wishlist' => $wishlist,
            'addresses' => $addresses,
            'notifications' => $this->notificationModel->getNotificationsByUser($userId),
            'notifications_paginated' => $this->notificationModel->getNotificationsPaginated($userId, $notiPerPage, $notiOffset),
            'noti_page' => $notiPage,
            'noti_total_pages' => $totalNotiPages,
            'noti_total_count' => $totalNotiCount,
            'saved_vouchers' => $this->userModel->getSavedVouchers($userId)
        ];

        $this->view('user/profile', $data);
    }



    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'gender' => trim($_POST['gender'])
            ];

            // Kiểm tra định dạng số điện thoại hợp lệ (chỉ số, khoảng trắng, gạch ngang hoặc +, từ 9-15 ký tự)
            if (!preg_match('/^\+?[0-9\s\-]{9,15}$/', $data['phone'])) {
                header('Location: ' . URLROOT . '/user/profile?tab=profile&error=invalid_phone');
                exit();
            }

            if ($this->userModel->updateProfile($userId, $data)) {
                // Cập nhật session hiển thị
                $_SESSION['user_name'] = $data['full_name'];
                $_SESSION['user_avatar'] = 'https://ui-avatars.com/api/?name=' . urlencode($data['full_name']) . '&background=0453cd&color=fff';
                
                header('Location: ' . URLROOT . '/user/profile?tab=profile&success=1');
            } else {
                header('Location: ' . URLROOT . '/user/profile?tab=profile&error=1');
            }
        }
    }

    public function addAddress() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'customer_id' => $_SESSION['customer_id'],
                'receiver_name' => trim($_POST['receiver_name']),
                'receiver_phone' => trim($_POST['receiver_phone']),
                'province' => trim($_POST['province']),
                'district' => trim($_POST['district']),
                'ward' => trim($_POST['ward']),
                'address_detail' => trim($_POST['address_detail']),
                'is_default' => isset($_POST['is_default']) ? 1 : 0
            ];

            if ($data['is_default']) {
                $this->addressModel->setDefault(0, $data['customer_id']); // Reset others
            }

            if ($this->addressModel->addAddress($data)) {
                header('Location: ' . URLROOT . '/user/profile?tab=addresses&success=added');
            } else {
                header('Location: ' . URLROOT . '/user/profile?tab=addresses&error=added');
            }
        }
    }

    public function setDefaultAddress($id) {
        $customerId = $_SESSION['customer_id'];
        $userId = $_SESSION['user_id'];
        
        if ($this->addressModel->setDefault($id, $customerId)) {
            // Lấy thông tin địa chỉ vừa đặt làm mặc định để đồng bộ qua hồ sơ
            $addresses = $this->addressModel->getAddressesByCustomer($customerId);
            $defaultAddr = null;
            foreach ($addresses as $addr) {
                if ($addr['id'] == $id) {
                    $defaultAddr = $addr;
                    break;
                }
            }

            if ($defaultAddr) {
                $profileData = [
                    'full_name' => $defaultAddr['receiver_name'],
                    'phone' => $defaultAddr['receiver_phone'],
                    'address' => $defaultAddr['address_detail'] . ', ' . $defaultAddr['ward'] . ', ' . $defaultAddr['district'] . ', ' . $defaultAddr['province'],
                    'gender' => $user['gender'] ?? 'Khác' // Giữ nguyên giới tính khi sync địa chỉ
                ];
                
                $this->userModel->updateProfile($userId, $profileData);
                
                // Cập nhật session hiển thị
                $_SESSION['user_name'] = $profileData['full_name'];
                $_SESSION['user_avatar'] = 'https://ui-avatars.com/api/?name=' . urlencode($profileData['full_name']) . '&background=0453cd&color=fff';
            }

            header('Location: ' . URLROOT . '/user/profile?tab=addresses&success=default');
        } else {
            header('Location: ' . URLROOT . '/user/profile?tab=addresses&error=default');
        }
    }

    public function deleteAddress($id) {
        $customerId = $_SESSION['customer_id'];
        if ($this->addressModel->deleteAddress($id, $customerId)) {
            header('Location: ' . URLROOT . '/user/profile?tab=addresses&success=deleted');
        } else {
            header('Location: ' . URLROOT . '/user/profile?tab=addresses&error=deleted');
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            // Kiểm tra mật khẩu hiện tại
            $hashedPassword = $this->userModel->getPassword($userId);
            if (password_verify($currentPassword, $hashedPassword)) {
                if ($newPassword === $confirmPassword) {
                    if ($this->userModel->updatePassword($userId, $newPassword)) {
                        header('Location: ' . URLROOT . '/user/profile?tab=security&success=password');
                    } else {
                        header('Location: ' . URLROOT . '/user/profile?tab=security&error=db');
                    }
                } else {
                    header('Location: ' . URLROOT . '/user/profile?tab=security&error=match');
                }
            } else {
                header('Location: ' . URLROOT . '/user/profile?tab=security&error=current');
            }
            exit();
        }
    }

    public function orderDetail($id) {
        $customerId = $_SESSION['customer_id'];
        $order = $this->orderModel->getOrderById($id);
        
        // Bảo mật: Chỉ cho phép xem đơn hàng của chính mình
        if (!$order || $order['customer_id'] != $customerId) {
            header('Location: ' . URLROOT . '/user/profile?tab=orders');
            exit();
        }

        // Xóa thông báo đặt hàng thành công ở trang chủ nếu thanh toán thất bại/bị hủy
        if (isset($_GET['error']) && $_GET['error'] === 'payment_failed') {
            unset($_SESSION['order_success_flash']);
            unset($_SESSION['order_success_id']);
        }

        $items = $this->orderModel->getOrderItems($id);
        
        $statusMapping = [
            'pending' => __('order_status_pending', 'Chờ xử lý'),
            'processing' => __('order_status_processing', 'Đang xử lý'),
            'shipping' => __('order_status_shipping', 'Đang giao'),
            'shipped' => __('order_status_shipping', 'Đang giao'),
            'delivered' => __('order_status_completed', 'Đã giao'),
            'completed' => __('order_status_completed', 'Hoàn thành'),
            'cancelled' => __('order_status_cancelled', 'Đã hủy')
        ];

        $paymentMapping = [
            'pending' => __('payment_status_unpaid', 'Chưa thanh toán'),
            'paid' => __('payment_status_paid', 'Đã thanh toán'),
            'refunded' => __('payment_status_refunded', 'Đã hoàn tiền')
        ];

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price_at_purchase'] * $item['quantity'];
        }

        $total = (float)$order['total_amount'];
        $order['formatted_date'] = date('d/m/Y H:i', strtotime($order['ordered_at']));
        $order['formatted_total'] = number_format($total, 0, ',', '.');
        $order['formatted_subtotal'] = number_format($subtotal, 0, ',', '.');
        $order['status_text'] = $statusMapping[strtolower($order['order_status'])] ?? 'Không rõ';
        $order['payment_status_text'] = $paymentMapping[strtolower($order['payment_status'])] ?? 'Chưa xác định';

        $data = [
            'title' => 'Chi tiết đơn hàng #' . $order['id'],
            'noindex' => true,
            'order' => $order,
            'items' => $items,
            'user' => $_SESSION // Truyền session để header hiển thị avatar
        ];

        $this->view('user/order_detail', $data);
    }

    public function cancelOrder($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customerId = $_SESSION['customer_id'] ?? null;
            
            if (!$customerId) {
                header('Location: ' . URLROOT . '/user/orderDetail/' . $id . '?error=no_session');
                exit();
            }

            if ($this->orderModel->cancelOrder($id, $customerId)) {
                // Trả lại số lượng kho
                $items = $this->orderModel->getOrderItems($id);
                foreach ($items as $item) {
                    $this->productModel->increaseStock($item['product_id'], $item['quantity']);
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

    public function getNotifications() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $notifications = $this->userModel->getNotifications($userId);
        $unreadCount = $this->userModel->getUnreadNotificationsCount($userId);
        
        echo json_encode([
            'status' => 'success',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markNotificationsRead() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $id = $_POST['id'] ?? null;
        
        if ($this->userModel->markNotificationAsRead($userId, $id)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}



