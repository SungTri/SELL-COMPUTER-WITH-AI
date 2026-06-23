<?php

class FeedbackController extends Controller {
    private $feedbackModel;

    public function __construct() {
        $this->feedbackModel = $this->model('FeedbackModel');
    }

    public function index() {
        header('Location: ' . URLROOT . '/page/contact');
        exit();
    }

    /**
     * Gửi ý kiến góp ý
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/page/contact');
            exit();
        }

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['customer_id'])) {
            header('Location: ' . URLROOT . '/auth/login?error=' . urlencode('Vui lòng đăng nhập để gửi góp ý.'));
            exit();
        }

        $customerId = $_SESSION['customer_id'];
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        // Validate đầu vào
        if (empty($title) || empty($content)) {
            header('Location: ' . URLROOT . '/page/contact?error=' . urlencode('Tiêu đề và nội dung góp ý không được để trống.'));
            exit();
        }

        // Lưu góp ý
        if ($this->feedbackModel->addFeedback($customerId, $title, $content)) {
            header('Location: ' . URLROOT . '/page/contact?msg=' . urlencode('Cảm ơn bạn đã gửi góp ý! Chúng tôi sẽ phản hồi sớm nhất có thể.'));
        } else {
            header('Location: ' . URLROOT . '/page/contact?error=' . urlencode('Đã xảy ra lỗi hệ thống khi lưu góp ý. Vui lòng thử lại.'));
        }
        exit();
    }
}
