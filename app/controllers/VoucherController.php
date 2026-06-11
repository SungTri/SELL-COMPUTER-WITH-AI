<?php

class VoucherController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    public function save($voucherId) {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để lưu mã!']);
            return;
        }

        if ($this->userModel->saveVoucher($_SESSION['user_id'], $voucherId)) {
            echo json_encode(['status' => 'success', 'message' => 'Lưu mã thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể lưu mã lúc này.']);
        }
    }

    public function myVouchers() {
        if (!isset($_SESSION['customer_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $vouchers = $this->userModel->getSavedVouchers($_SESSION['customer_id']);
        
        $data = [
            'title' => 'Khuyến mãi của tôi',
            'vouchers' => $vouchers
        ];
        
        $this->view('user/vouchers', $data);
    }
}
