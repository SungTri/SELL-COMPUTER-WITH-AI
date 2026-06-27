<?php

class CheckoutController extends Controller {
    private $productModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->orderModel = $this->model('OrderModel');
        // Require login to access checkout
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login?redirect=checkout');
            exit();
        }
    }

    public function index() {
        // Get cart items
        $cartModel = $this->model('CartModel');
        $cartItems = [];
        $total = 0;
        
        if (isset($_SESSION['customer_id'])) {
            $cart = $cartModel->getCartByCustomerId($_SESSION['customer_id']);
            if ($cart) {
                $cartItems = $cartModel->getItems($cart['id']);
            }
        } elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $cartItems = array_values($_SESSION['cart']);
        }
        
        // If cart is empty, redirect to cart page
        if (empty($cartItems)) {
            header('Location: ' . URLROOT . '/cart');
            exit();
        }
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $customerId = $_SESSION['customer_id'] ?? null;
        $userProfile = [];
        $defaultAddress = null;
        $shippingFee = 30000; // Mặc định ban đầu
        $shippingFees = $this->orderModel->getAllShippingFees();

        if ($customerId) {
            $userModel = $this->model('UserModel');
            $userProfile = $userModel->getUserProfile($_SESSION['user_id'] ?? 0);
            
            $addressModel = $this->model('AddressModel');
            $addresses = $addressModel->getAddressesByCustomer($customerId);
            if (!empty($addresses)) {
                $defaultAddress = $addresses[0]; // addresses sorted by is_default DESC
                if (!empty($defaultAddress['province'])) {
                    $shippingFee = $this->orderModel->getShippingFeeByProvince($defaultAddress['province']);
                }
            }
        }

        // Voucher check
        $discountAmount = 0;
        $appliedVoucher = $_SESSION['applied_voucher'] ?? null;
        $isFreeship = false;

        if ($appliedVoucher) {
            $adminModel = $this->model('AdminModel');
            $voucher = $adminModel->getVoucherByCode($appliedVoucher);
            if ($voucher) {
                if ($voucher['is_freeship'] == 1) {
                    $isFreeship = true;
                    $discountAmount = $shippingFee;
                } elseif ($voucher['discount_percentage'] > 0) {
                    $discountAmount = ($total * $voucher['discount_percentage']) / 100;
                } else {
                    $discountAmount = $voucher['discount_amount'];
                }
            } else {
                unset($_SESSION['applied_voucher']);
                $appliedVoucher = null;
            }
        }

        $totalPayment = $total + ($isFreeship ? 0 : $shippingFee) - ($isFreeship ? 0 : $discountAmount);
        if ($totalPayment < 0) {
            $totalPayment = 0;
        }

        $data = [
            'title' => 'Thanh toán - TechExpert',
            'noindex' => true,
            'cartItems' => $cartItems,
            'subTotal' => $total,
            'shippingFee' => $shippingFee,
            'discountAmount' => $discountAmount,
            'total' => $totalPayment,
            'userProfile' => $userProfile,
            'defaultAddress' => $defaultAddress,
            'appliedVoucher' => $appliedVoucher,
            'isFreeship' => $isFreeship,
            'shippingFees' => $shippingFees
        ];

        $this->view('checkout/index', $data);
    }


    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullName = $_POST['fullName'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $province = $_POST['province'] ?? '';
            $district = $_POST['district'] ?? '';
            $ward = $_POST['ward'] ?? '';
            $address = $_POST['address'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'COD';
            $voucherCode = $_POST['voucher_code'] ?? '';
            
            // Format full address
            $fullAddress = trim("$address, $ward, $district, $province");
            
            $customerId = $_SESSION['customer_id'] ?? null;
            
            if (!$customerId) {
                header('Location: ' . URLROOT . '/cart');
                exit();
            }
            
            $cartModel = $this->model('CartModel');
            $cart = $cartModel->getCartByCustomerId($customerId);
            
            if (!$cart) {
                header('Location: ' . URLROOT . '/cart');
                exit();
            }
            
            $cartItems = $cartModel->getItems($cart['id']);
            
            if (empty($cartItems)) {
                header('Location: ' . URLROOT . '/cart');
                exit();
            }

            // 1. Check Stock for all items first
            $stockErrors = [];
            foreach ($cartItems as $item) {
                $productId = isset($item['product_id']) ? $item['product_id'] : $item['id'];
                $variantId = isset($item['variant_id']) ? $item['variant_id'] : null;
                $stockCheck = $this->productModel->checkStock($productId, $item['quantity'], $variantId);
                
                if ($stockCheck['status'] !== 'ok') {
                    if ($stockCheck['status'] === 'insufficient') {
                        $stockErrors[] = "Sản phẩm '{$stockCheck['name']}' hiện chỉ còn {$stockCheck['available']} chiếc trong kho, vui lòng giảm số lượng hoặc chọn sản phẩm khác.";
                    } else {
                        $stockErrors[] = $stockCheck['message'];
                    }
                }
            }

            if (!empty($stockErrors)) {
                $_SESSION['checkout_error'] = implode("<br>", $stockErrors);
                header('Location: ' . URLROOT . '/checkout');
                exit();
            }

            
            $subTotal = 0;
            foreach ($cartItems as $item) {
                $subTotal += $item['price'] * $item['quantity'];
            }
            
            $shippingFee = $this->orderModel->getShippingFeeByProvince($province);
            $totalAmount = $subTotal + $shippingFee;
            $discountAmount = 0;
            $voucherId = null;

            // Handle Voucher
            if (!empty($voucherCode)) {
                $adminModel = $this->model('AdminModel');
                $voucher = $adminModel->getVoucherByCode($voucherCode);
                
                if ($voucher) {
                    // Check if user already used this voucher
                    $actualCustomerId = $_SESSION['customer_id'] ?? $_SESSION['user_id'] ?? $customerId;
                    $actualUserId = $_SESSION['user_id'] ?? $_SESSION['customer_id'] ?? 0;
                    
                    $notificationModel = $this->model('NotificationModel');
                    $hasNewPromotion = $notificationModel->hasPromotionForVoucher($actualUserId, $voucherCode);

                    // Only check isUsed if there is no special promotion from Admin
                    if (!$hasNewPromotion) {
                        $isUsed = $adminModel->isVoucherUsedByUser($voucher['id'], $actualCustomerId);
                        if ($isUsed) {
                            header('Location: ' . URLROOT . '/checkout?error=voucher_already_used');
                            exit();
                        }
                    }

                    // Date check bypassed to avoid timezone issues
                    if (true) {
                        if ($voucher['is_freeship'] == 1) {
                            $discountAmount = $shippingFee; // Recording the shipping discount
                            $shippingFee = 0;
                        } elseif ($voucher['discount_percentage'] > 0) {
                            $discountAmount = ($subTotal * $voucher['discount_percentage']) / 100;
                        } else {
                            $discountAmount = $voucher['discount_amount'];
                        }
                        $voucherId = $voucher['id'];
                        $totalAmount = $subTotal + $shippingFee - ($voucher['is_freeship'] == 1 ? 0 : $discountAmount);
                        if ($totalAmount < 0) $totalAmount = 0;
                    }
                }
            }
            
            $orderModel = $this->model('OrderModel');
            
            // Create order
            $orderId = $orderModel->createOrder($customerId, $totalAmount, $paymentMethod, $fullAddress, $voucherId, $discountAmount, $shippingFee);
            
            if ($orderId) {
                // Add order items and deduct stock
                foreach ($cartItems as $item) {
                    $orderModel->addOrderItems($orderId, [$item]);
                    if (!empty($item['variant_id'])) {
                        $this->productModel->decreaseVariantStock($item['variant_id'], $item['quantity']);
                    } else {
                        $this->productModel->decreaseStock($item['product_id'], $item['quantity']);
                    }
                }
                
                // Clear cart in DB
                $cartModel->clearCart($cart['id']);
                
                // Clear the promotion notification for this user if they used a voucher
                if (!empty($voucherCode)) {
                    $notificationModel = $this->model('NotificationModel');
                    $userId = $_SESSION['user_id'] ?? null;
                    if ($userId) {
                        $notificationModel->deletePromotionByVoucherCode($userId, $voucherCode);
                    }
                }
                
                // Clear cart in Session
                $_SESSION['cart'] = [];
                unset($_SESSION['applied_voucher']);

                // Thông báo cho Admin
                $notificationModel = $this->model('NotificationModel');
                $notificationModel->createNotification(
                    1, 
                    "Đơn hàng mới #$orderId", 
                    "Khách hàng $fullName vừa đặt một đơn hàng mới trị giá " . number_format($totalAmount, 0, ',', '.') . " đ", 
                    'order'
                );

                // Gửi email xác nhận đặt hàng cho khách hàng
                try {
                    $userModel = $this->model('UserModel');
                    $userProfile = $userModel->getUserProfile($_SESSION['user_id']);
                    if ($userProfile && !empty($userProfile['email'])) {
                        $orderData = $orderModel->getOrderById($orderId);
                        $orderItemsData = $orderModel->getOrderItems($orderId);
                        
                        $emailService = new EmailService();
                        $emailService->sendOrderConfirmationEmail(
                            $userProfile['email'],
                            $fullName ?: ($userProfile['full_name'] ?: 'Khách hàng'),
                            $orderData,
                            $orderItemsData
                        );
                    }
                } catch (Exception $e) {
                    error_log("Lỗi gửi email xác nhận đơn hàng: " . $e->getMessage());
                }

                // Lưu session flash thông báo đặt hàng thành công để hiển thị ở trang chủ
                $_SESSION['order_success_flash'] = "Đặt hàng thành công! Đơn hàng #$orderId của bạn đang được chuẩn bị để giao hàng.";
                $_SESSION['order_success_id'] = $orderId;
                
                header('Location: ' . URLROOT . '/checkout/success?order_id=' . $orderId);
                exit();
            }
        }
        
        header('Location: ' . URLROOT . '/checkout');
        exit();
    }

    public function applyVoucher() {
        // Temporarily enable error logging to catch the silent crash
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', APPROOT . '/../ajax_fatal.log');
        ob_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = isset($_POST['code']) ? trim($_POST['code']) : '';
            $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
            $province = isset($_POST['province']) ? trim($_POST['province']) : '';
            
            if (empty($code)) {
                ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mã giảm giá']);
                return;
            }

            $adminModel = $this->model('AdminModel');
            $notificationModel = $this->model('NotificationModel');
            $voucher = $adminModel->getVoucherByCode($code);
            
            if (!$voucher) {
                ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn']);
                return;
            }

            $customerId = $_SESSION['customer_id'] ?? $_SESSION['user_id'] ?? null;
            $userId = $_SESSION['user_id'] ?? $_SESSION['customer_id'] ?? null;
            
            // Try to find if there's a promotion for this voucher
            $hasNewPromotion = false;
            if ($userId) {
                $hasNewPromotion = $notificationModel->hasPromotionForVoucher($userId, $code);
            }

            // Check if user already used this voucher - ONLY if not a new promotion from Admin
            if (!$hasNewPromotion) {
                $isUsed = $customerId ? $adminModel->isVoucherUsedByUser($voucher['id'], $customerId) : false;
                if ($isUsed) {
                    ob_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Bạn đã sử dụng mã giảm giá này cho một đơn hàng khác rồi']);
                    return;
                }
            }
            
            // Date check bypassed to avoid timezone issues
            
            $shippingFee = $this->orderModel->getShippingFeeByProvince($province);
            $discount = 0;
            $isFreeship = $voucher['is_freeship'] == 1;
            
            if ($isFreeship) {
                $discount = $shippingFee; 
                $finalShipping = 0;
                $finalDiscount = 0; // Don't subtract discount if it's just freeship
            } elseif ($voucher['discount_percentage'] > 0) {
                $discount = ($subtotal * $voucher['discount_percentage']) / 100;
                $finalShipping = $shippingFee;
                $finalDiscount = $discount;
            } else {
                $discount = $voucher['discount_amount'];
                $finalShipping = $shippingFee;
                $finalDiscount = $discount;
            }
            
            $newTotal = $subtotal + $finalShipping - $finalDiscount;

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'is_freeship' => (bool)$isFreeship,
                'discount' => $discount,
                'discount_display' => number_format($discount, 0, ',', '.') . ' đ',
                'shipping_fee' => $finalShipping,
                'shipping_fee_display' => $finalShipping == 0 ? ($isFreeship ? 'Miễn phí' : '0 đ') : number_format($finalShipping, 0, ',', '.') . ' đ',
                'new_total' => $newTotal,
                'new_total_display' => number_format($newTotal, 0, ',', '.') . ' đ'
            ]);
            $_SESSION['applied_voucher'] = $code;
            return;
        }
    }
    
    public function success() {
        $orderId = $_GET['order_id'] ?? null;
        
        if (!$orderId) {
            header('Location: ' . URLROOT);
            exit();
        }

        $order = $this->orderModel->getOrderById($orderId);
        $isPaid = strtolower($order['payment_status'] ?? '') === 'paid';
        
        $pageTitle = 'Đặt hàng thành công - TechExpert';
        if ($order['payment_method'] === 'BANKING' && !$isPaid) {
            $pageTitle = 'Thanh toán đơn hàng #' . $orderId . ' - TechExpert';
        }

        $items = $this->orderModel->getOrderItems($orderId);

        $data = [
            'title' => $pageTitle,
            'noindex' => true,
            'orderId' => $orderId,
            'order' => $order,
            'items' => $items
        ];
        
        $this->view('checkout/success', $data);
    }

    public function devPay() {
        // Only allow on localhost environment for security
        if (strpos(URLROOT, 'localhost') === false) {
            die('Access denied.');
        }
        
        $orderId = $_GET['order_id'] ?? null;
        if (!$orderId) {
            die('No order ID provided.');
        }
        
        $success = $this->orderModel->updatePaymentStatus($orderId, 'Paid');
        if ($success) {
            echo "<h1>Thanh toán thành công đơn hàng #$orderId!</h1>";
            echo "<p>Trang thanh toán checkout success đang mở của bạn sẽ tự động nhận diện và chuyển hướng sau 3 giây.</p>";
        } else {
            echo "<h1>Lỗi cập nhật đơn hàng hoặc đơn hàng không tồn tại.</h1>";
        }
    }

    public function getAvailableVouchers() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            return;
        }

        $adminModel = $this->model('AdminModel');
        $notificationModel = $this->model('NotificationModel');
        
        $vouchers = $adminModel->getAllVouchers(); 
        $notifications = $notificationModel->getNotificationsByUser($_SESSION['user_id']);
        
        $activeVouchers = [];
        $today = date('Y-m-d');
        
        // Extract promotion messages
        $promoMessages = array_filter($notifications, function($n) {
            return $n['type'] === 'promotion';
        });

        $customerId = $_SESSION['customer_id'] ?? null;

        foreach ($vouchers as $v) {
            if ($v['status'] == 1 && $today >= $v['start_date'] && $today <= $v['end_date']) {
                // Check if user already used this voucher
                $isUsed = $customerId ? $adminModel->isVoucherUsedByUser($v['id'], $customerId) : false;
                
                // Check if the voucher code is mentioned in user's promotion notifications
                $isSentToUser = false;
                foreach ($promoMessages as $msg) {
                    if (stripos($msg['content'], $v['code']) !== false || stripos($msg['title'], $v['code']) !== false) {
                        $isSentToUser = true;
                        break;
                    }
                }

                // Show voucher if:
                // 1. It's NOT used yet (Public vouchers)
                // 2. OR it was sent specifically by Admin (allows reuse)
                if (!$isUsed || $isSentToUser) {
                    $activeVouchers[] = $v;
                }
            }
        }
        
        echo json_encode($activeVouchers);
    }

    public function checkPaymentStatus() {
        ob_start();
        error_reporting(0);
        ini_set('display_errors', 0);

        $orderId = $_GET['order_id'] ?? null;
        if (!$orderId) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'No order ID']);
            return;
        }

        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Order not found']);
            return;
        }

        $isPaid = strtolower($order['payment_status']) === 'paid';
        
        // Active Casso API Polling
        $cassoApiKey = $_ENV['CASSO_API_KEY'] ?? null;
        $logPath = APPROOT . '/../casso_api_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        
        if (!$isPaid) {
            if (!$cassoApiKey || $cassoApiKey === 'YOUR_CASSO_API_KEY_HERE') {
                $logMsg = "[$timestamp] Order #$orderId - Status: pending - CASSO_API_KEY is empty/placeholder. Waiting for Webhook.\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
            } else {
                // Fetch transactions from Casso API
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://oauth.casso.vn/v2/transactions?pageSize=15&sort=DESC");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Apikey " . $cassoApiKey,
                    "Content-Type: application/json"
                ]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 6);
                $response = curl_exec($ch);
                $curlErr = curl_error($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($response === false) {
                    $logMsg = "[$timestamp] Order #$orderId - Status: pending - Casso API Connection Failed. Curl Error: $curlErr\n";
                    file_put_contents($logPath, $logMsg, FILE_APPEND);
                } else if ($httpCode !== 200) {
                    $logMsg = "[$timestamp] Order #$orderId - Status: pending - Casso API Error. HTTP Code: $httpCode. Response: $response\n";
                    file_put_contents($logPath, $logMsg, FILE_APPEND);
                } else {
                    $resData = json_decode($response, true);
                    if (isset($resData['error']) && $resData['error'] === 0 && isset($resData['data']['records'])) {
                        $records = $resData['data']['records'];
                        $foundMatch = false;
                        
                        foreach ($records as $record) {
                            $desc = $record['description'] ?? '';
                            $amount = $record['amount'] ?? 0;
                            $tid = $record['id'] ?? '';
                            $when = $record['when'] ?? '';

                            // 1. Strict Match: Extract Order ID from description
                            $isMatch = false;
                            $isFallback = false;
                            preg_match('/DH\s*(\d+)/i', $desc, $matches);
                            
                            if (isset($matches[1])) {
                                if ((int)$matches[1] === (int)$orderId) {
                                    $isMatch = true;
                                }
                            } else {
                                // 2. Fallback Match: Check by exact amount and recent time
                                $orderTime = strtotime($order['ordered_at']);
                                $txTime = strtotime($when);
                                
                                // Check if amount matches exactly, and transaction time is within 1 hour of order creation
                                // We also check if txTime is after orderTime - 120 (with a 2-min clock skew tolerance)
                                if (abs($amount - $order['total_amount']) < 1 && 
                                    $txTime >= ($orderTime - 120) && 
                                    $txTime <= ($orderTime + 3600)) {
                                    $isMatch = true;
                                    $isFallback = true;
                                }
                            }

                            if ($isMatch) {
                                $foundMatch = true;
                                if (!$this->orderModel->isTransactionProcessed($tid)) {
                                    if ($amount >= $order['total_amount']) {
                                        // Update order status to paid in database
                                        $this->orderModel->updatePaymentStatus($orderId, 'Paid');
                                        
                                        // Log transaction inside bank_transactions
                                        $this->orderModel->logBankTransaction([
                                            'transaction_id' => $tid,
                                            'amount' => $amount,
                                            'description' => $desc,
                                            'order_id' => $orderId,
                                            'status' => $isFallback ? 'Success (Fallback)' : 'Success'
                                        ]);
                                        
                                        $isPaid = true;
                                        $logMsg = "[$timestamp] Order #$orderId - Paid! Transaction matched successfully" . ($isFallback ? " (Fallback)" : "") . ". ID: $tid, Amount: $amount, Time: $when, Description: $desc\n";
                                        file_put_contents($logPath, $logMsg, FILE_APPEND);
                                        break;
                                    } else {
                                        $logMsg = "[$timestamp] Order #$orderId - Match found but Insufficient Amount! ID: $tid, Amount: $amount, Required: {$order['total_amount']}, Description: $desc\n";
                                        file_put_contents($logPath, $logMsg, FILE_APPEND);
                                    }
                                } else {
                                    // Already processed, but it means order should have been paid or is logged. Let's force check paid state
                                    $isPaid = true;
                                    $logMsg = "[$timestamp] Order #$orderId - Match found but transaction already processed. ID: $tid, Description: $desc\n";
                                    file_put_contents($logPath, $logMsg, FILE_APPEND);
                                    break;
                                }
                            }
                        }
                        
                        if (!$foundMatch) {
                            $logMsg = "[$timestamp] Order #$orderId - Status: pending - No matching transaction found in the last 15 Casso records.\n";
                            file_put_contents($logPath, $logMsg, FILE_APPEND);
                        }
                    } else {
                        $logMsg = "[$timestamp] Order #$orderId - Status: pending - Invalid Casso response structure: $response\n";
                        file_put_contents($logPath, $logMsg, FILE_APPEND);
                    }
                }
            }
        }

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'is_paid' => $isPaid,
            'order_status' => $order['status']
        ]);
    }
}
