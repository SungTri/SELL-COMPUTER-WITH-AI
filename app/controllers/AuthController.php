<?php

class AuthController extends Controller {
    public function login() {
        $data = [
            'title' => 'Đăng nhập - TechExpert',
            'noindex' => true
        ];
        $this->view('auth/login', $data);
    }

    public function register() {
        $data = [
            'title' => 'Đăng ký - TechExpert',
            'noindex' => true
        ];
        $this->view('auth/register', $data);
    }

    public function forgot_password() {
        $data = [
            'title' => 'Quên mật khẩu - TechExpert',
            'noindex' => true
        ];
        $this->view('auth/forgot_password', $data);
    }

    public function forgot_password_process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == 1) || 
                      (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByEmail($email);
            
            if ($user && $user['status'] == 1) {
                // Generate a random new password (8 characters)
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $newPassword = '';
                for ($i = 0; $i < 8; $i++) {
                    $newPassword .= $chars[rand(0, strlen($chars) - 1)];
                }
                
                // Update password in DB (hashed)
                $userModel->updatePassword($user['id'], $newPassword);
                
                // Send new password via email
                $emailService = new EmailService();
                $toName = $user['full_name'] ?? 'Khách hàng';
                $emailService->sendNewPasswordEmail($email, $toName, $newPassword);
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Chúng tôi đã cấp mật khẩu mới và gửi tới địa chỉ email: ' . htmlspecialchars($email)
                    ]);
                    exit();
                }
                
                $data = [
                    'title' => 'Cấp lại mật khẩu thành công - TechExpert',
                    'noindex' => true,
                    'email' => $email
                ];
                
                $this->view('auth/forgot_password_success', $data);
                exit();
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Email không tồn tại trên hệ thống hoặc tài khoản chưa kích hoạt.'
                    ]);
                    exit();
                }
                header('Location: ' . URLROOT . '/auth/forgot_password?error=notfound');
                exit();
            }
        }
        
        $isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == 1) || 
                  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ.'
            ]);
            exit();
        }
        header('Location: ' . URLROOT . '/auth/forgot_password');
        exit();
    }

    public function reset_password() {
        $token = $_GET['token'] ?? '';
        
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserByResetToken($token);
        
        if (!$user) {
            header('Location: ' . URLROOT . '/auth/login?error=invalid_token');
            exit();
        }
        
        $data = [
            'title' => 'Đặt lại mật khẩu - TechExpert',
            'noindex' => true,
            'token' => $token
        ];
        
        $this->view('auth/reset_password', $data);
    }
    
    public function reset_password_process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if ($password !== $confirmPassword) {
                header('Location: ' . URLROOT . '/auth/reset_password?token=' . urlencode($token) . '&error=mismatch');
                exit();
            }
            
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByResetToken($token);
            
            if ($user) {
                $userModel->updatePasswordWithToken($token, $password);
                header('Location: ' . URLROOT . '/auth/login?success=password_reset');
                exit();
            } else {
                header('Location: ' . URLROOT . '/auth/login?error=invalid_token');
                exit();
            }
        }
        header('Location: ' . URLROOT . '/auth/login');
        exit();
    }

    public function auth() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Kiểm tra trạng thái tài khoản
                if ($user['status'] == 0) {
                    header('Location: ' . URLROOT . '/auth/login?error=inactive');
                    exit();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role_id'];
                $_SESSION['user_name'] = $user['full_name'] ?? ($user['role_id'] == 1 ? 'Admin' : 'Khách hàng');
                $_SESSION['user_email'] = $user['email'];
                
                // Get customer_id
                $db = new Database();
                $db->query("SELECT id FROM customers WHERE user_id = :user_id");
                $db->bind(':user_id', $user['id']);
                $customer = $db->single();
                if ($customer) {
                    $_SESSION['customer_id'] = $customer['id'];
                    
                    // Sync guest cart to DB
                    $cartModel = $this->model('CartModel');
                    $cart = $cartModel->getCartByCustomerId($customer['id']);
                    if (!$cart) {
                        $cartId = $cartModel->createCart($customer['id']);
                        $cart = ['id' => $cartId];
                    }

                    // If there's a guest cart, add items to DB
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $productId => $item) {
                            $cartModel->addItem($cart['id'], $productId, $item['quantity']);
                        }
                    }

                    // Now load final cart from DB back to session for consistency
                    $items = $cartModel->getItems($cart['id']);
                    $_SESSION['cart'] = [];
                    foreach ($items as $item) {
                        $_SESSION['cart'][$item['product_id']] = [
                            'id' => $item['product_id'],
                            'name' => $item['name'],
                            'specs' => $item['specs'],
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'image' => $item['image']
                        ];
                    }
                }

                $_SESSION['user_avatar'] = 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name']) . '&background=0453cd&color=fff';
                
                // Redirect dựa trên role
                if ($user['role_id'] == 1) {
                    header('Location: ' . URLROOT . '/admin');
                } else {
                    header('Location: ' . URLROOT);
                }
                exit();
            } else {
                // Sai email hoặc mật khẩu
                header('Location: ' . URLROOT . '/auth/login?error=invalid&email=' . urlencode($email));
                exit();
            }
        }
        
        header('Location: ' . URLROOT . '/auth/login');
        exit();
    }

    public function register_process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullName = trim($_POST['fullName'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            
            $userModel = $this->model('UserModel');
            
            // Query params to retain inputs
            $queryParams = '&fullName=' . urlencode($fullName) . '&email=' . urlencode($email) . '&phone=' . urlencode($phone);

            // Kiểm tra định dạng số điện thoại hợp lệ (chỉ số, khoảng trắng, gạch ngang hoặc +, từ 9-15 ký tự)
            if (!preg_match('/^\+?[0-9\s\-]{9,15}$/', $phone)) {
                header('Location: ' . URLROOT . '/auth/register?error=invalid_phone' . $queryParams);
                exit();
            }

            // Check if passwords match
            if ($password !== $confirmPassword) {
                header('Location: ' . URLROOT . '/auth/register?error=mismatch' . $queryParams);
                exit();
            }

            // Check if email already exists
            if ($userModel->getUserByEmail($email)) {
                header('Location: ' . URLROOT . '/auth/register?error=email_exists' . $queryParams);
                exit();
            }

            // Generate secure token and expiry time (24 hours)
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

            // Register pending user (status = 0) with token
            $userId = $userModel->registerPending($email, $password, $token, $expires);
            
            if ($userId) {
                // Create customer profile
                $userModel->createCustomerProfile($userId, $fullName, $phone);
                
                // Send activation email
                $activationLink = URLROOT . '/auth/verify?token=' . $token;
                
                // Load and run EmailService (auto-loaded)
                $emailService = new EmailService();
                $emailService->sendActivationEmail($email, $fullName, $activationLink);
                
                // Redirect to waiting instructions page
                header('Location: ' . URLROOT . '/auth/register_waiting?email=' . urlencode($email));
                exit();
            } else {
                header('Location: ' . URLROOT . '/auth/register?error=failed' . $queryParams);
                exit();
            }
        }
        
        // Fallback
        header('Location: ' . URLROOT . '/auth/register');
        exit();
    }

    public function register_waiting() {
        $email = $_GET['email'] ?? '';
        $data = [
            'title' => 'Chờ kích hoạt tài khoản - TechExpert',
            'noindex' => true,
            'email' => $email
        ];
        $this->view('auth/register_waiting', $data);
    }

    public function verify() {
        $token = $_GET['token'] ?? '';
        
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserByActivationToken($token);
        
        if ($user) {
            // Check token expiration (24h)
            if (strtotime($user['token_expires_at']) > time()) {
                if ($userModel->activateUser($user['id'])) {
                    header('Location: ' . URLROOT . '/auth/login?success=activated');
                    exit();
                }
            } else {
                header('Location: ' . URLROOT . '/auth/login?error=activation_expired');
                exit();
            }
        }
        
        header('Location: ' . URLROOT . '/auth/login?error=activation_failed');
        exit();
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['customer_id']);
        session_destroy();
        header('Location: ' . URLROOT);
        exit();
    }
}

