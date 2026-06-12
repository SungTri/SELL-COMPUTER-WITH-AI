<?php

class ChatbotController extends Controller {
    private $chatbotModel;

    public function __construct() {
        $this->chatbotModel = $this->model('ChatbotModel');
    }

    public function sendMessage() {
        ob_start();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userMessage = trim($_POST['message'] ?? '');
            $mode = trim($_POST['mode'] ?? 'ai');
            if ($mode !== 'shop') {
                $mode = 'ai';
            }
            
            if (empty($userMessage)) {
                ob_end_clean();
                echo json_encode(['status' => 'error', 'message' => 'Empty message']);
                return;
            }

            $userId = $_SESSION['user_id'] ?? null;       // users.id for support_sessions
            $custId = $_SESSION['customer_id'] ?? null;   // customers.id for chat_history

            // Check if there is an active or pending support session for the user (only in shop mode)
            if ($userId && $mode === 'shop') {
                $db = new Database();
                $db->query("SELECT status FROM support_sessions WHERE customer_id = :customer_id");
                $db->bind(':customer_id', $userId);
                $session = $db->single();
                if ($session && ($session['status'] === 'pending' || $session['status'] === 'active')) {
                    // Log to history with [SHOP] prefix using customers.id
                    $this->logChat([
                        'customer_id' => $custId,
                        'question' => '[SHOP] ' . $userMessage,
                        'answer' => null
                    ]);

                    while (ob_get_level() > 0) ob_end_clean();
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'status' => 'success',
                        'live_chat' => true,
                        'bot_response' => null,
                        'time' => date('H:i')
                    ], JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }

            // --- High Confidence Local Matching Pre-check ---
            $localResponse = $this->findHighConfidenceLocalResponse($userMessage);
            if ($localResponse !== null) {
                $response = $localResponse;
            } else {
                // --- Gemini AI Integration ---
                $apiKey = GEMINI_API_KEY; 
                $response = "";

                try {
                    if ($apiKey === 'YOUR_GEMINI_API_KEY_HERE' || empty($apiKey)) {
                        $response = $this->findLocalResponse($userMessage);
                    } else if (!function_exists('curl_init')) {
                        $response = "Lỗi hệ thống: Thư viện CURL chưa được bật trong PHP.ini.";
                    } else {
                        $response = $this->getAIResponse($userMessage, $apiKey, $mode);
                    }
                } catch (Exception $e) {
                    $response = "Lỗi AI: " . $e->getMessage();
                }
            }
            
            // Log to history with database prefix to distinguish modes (use customers.id)
            $dbPrefix = ($mode === 'shop') ? '[SHOP] ' : '[AI] ';
            $this->logChat([
                'customer_id' => $custId,
                'question' => $dbPrefix . $userMessage,
                'answer' => $dbPrefix . $response,
            ]);

            // Lưu lịch sử vào Session nếu là khách vãng lai (không đăng nhập) để có trí nhớ
            if (!$userId) {
                $sessionKey = 'chat_history_' . $mode;
                if (!isset($_SESSION[$sessionKey])) {
                    $_SESSION[$sessionKey] = [];
                }
                $_SESSION[$sessionKey][] = [
                    'question' => $userMessage,
                    'answer' => $response
                ];
                // Giới hạn lịch sử session tối đa 10 lượt chat gần nhất
                if (count($_SESSION[$sessionKey]) > 10) {
                    array_shift($_SESSION[$sessionKey]);
                }
            }

            // Ensure clean output
            while (ob_get_level() > 0) ob_end_clean();
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'bot_response' => $response,
                'time' => date('H:i')
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    public function requestSupport() {
        ob_start();
        $userId = $_SESSION['user_id'] ?? null;     // users.id for support_sessions
        $custId = $_SESSION['customer_id'] ?? null; // customers.id for chat_history
        if (!$userId) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để sử dụng chức năng này.']);
            exit();
        }

        $db = new Database();
        
        // Check if there is an existing session (support_sessions uses users.id)
        $db->query("SELECT status FROM support_sessions WHERE customer_id = :customer_id");
        $db->bind(':customer_id', $userId);
        $session = $db->single();

        if ($session) {
            $db->query("UPDATE support_sessions SET status = 'pending', admin_id = NULL, updated_at = NOW() WHERE customer_id = :customer_id");
        } else {
            $db->query("INSERT INTO support_sessions (customer_id, status, admin_id, created_at, updated_at) VALUES (:customer_id, 'pending', NULL, NOW(), NOW())");
        }
        $db->bind(':customer_id', $userId);
        $db->execute();

        // Log system message in chat history using customers.id
        $dbPrefix = '[SHOP] ';
        $this->logChat([
            'customer_id' => $custId,
            'question' => $dbPrefix . 'yêu cầu hỗ trợ từ nhân viên.',
            'answer' => $dbPrefix . 'Hệ thống đang kết nối bạn với nhân viên hỗ trợ. Vui lòng chờ trong giây lát...',
        ]);

        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'message' => 'Đã gửi yêu cầu hỗ trợ.'
        ]);
        exit();
    }

    public function getHistory() {
        ob_start();
        $userId = $_SESSION['user_id'] ?? null;     // users.id for support_sessions
        $custId = $_SESSION['customer_id'] ?? null; // customers.id for chat_history
        if (!$userId) {
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $db = new Database();
        
        // Fetch support session status using users.id
        $db->query("SELECT s.status, s.admin_id, c.full_name as admin_name 
                    FROM support_sessions s 
                    LEFT JOIN customers c ON s.admin_id = c.user_id 
                    WHERE s.customer_id = :customer_id");
        $db->bind(':customer_id', $userId);
        $session = $db->single();
        $status = $session ? $session['status'] : 'closed';
        $adminName = $session ? ($session['admin_name'] ?? 'Admin') : 'Admin';

        // Fetch recent messages with prefix [SHOP] using customers.id
        if ($custId) {
            $db->query("SELECT * FROM chat_history 
                        WHERE customer_id = :customer_id 
                          AND (question LIKE '[SHOP]%' OR answer LIKE '[SHOP]%') 
                        ORDER BY chatted_at ASC");
            $db->bind(':customer_id', $custId);
            $messages = $db->resultSet();
        } else {
            $messages = [];
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
                    'sender' => 'bot',
                    'message' => substr($a, 7),
                    'time' => $time
                ];
            }
        }

        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'support_status' => $status,
            'admin_name' => $adminName,
            'messages' => $formatted
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }


    private function getAIResponse($message, $apiKey, $mode = 'ai') {
        $servicePath = APPROOT . '/services/GeminiService.php';
        require_once $servicePath;
        $gemini = new GeminiService($apiKey);
        $db = new Database();

        // Query shipping fees dynamically from database
        $orderModel = $this->model('OrderModel');
        $shippingFees = $orderModel->getAllShippingFees();
        $shippingFeesContextEn = "DYNAMIC SHIPPING FEES BY PROVINCE (If order is under 10,000,000 VND):\n";
        $shippingFeesContextVi = "PHÍ VẬN CHUYỂN CHI TIẾT THEO TỈNH THÀNH (Nếu đơn hàng dưới 10.000.000 VNĐ):\n";
        foreach ($shippingFees as $sf) {
            $shippingFeesContextEn .= "- " . $sf['province_name'] . ": " . number_format($sf['shipping_fee'], 0, ',', '.') . " VND\n";
            $shippingFeesContextVi .= "- " . $sf['province_name'] . ": " . number_format($sf['shipping_fee'], 0, ',', '.') . " VNĐ\n";
        }

        // Lấy tên khách hàng để cá nhân hóa lời thoại chào hỏi
        $userName = 'khách hàng';
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $userModel = $this->model('UserModel');
            $userProfile = $userModel->getUserProfile($userId);
            if ($userProfile && !empty($userProfile['full_name'])) {
                $userName = $userProfile['full_name'];
            }
        }

        $currentLang = $_SESSION['lang'] ?? 'vi';

        if ($mode === 'shop') {
            $customerId = $_SESSION['customer_id'] ?? null;
            $orderContext = "";
            if ($customerId) {
                $orderModel = $this->model('OrderModel');
                $rawOrders = $orderModel->getOrdersByCustomer($customerId);
                
                $recentOrders = array_slice($rawOrders, 0, 5);
                if (!empty($recentOrders)) {
                    $orderContext = ($currentLang === 'en') ? "Recent order details of the customer:\n" : "Thông tin đơn hàng gần đây của khách hàng:\n";
                    foreach ($recentOrders as $o) {
                        $orderId = $o['id'];
                        $oDetail = $orderModel->getOrderById($orderId);
                        $items = $orderModel->getOrderItems($orderId);
                        
                        $itemNames = [];
                        foreach ($items as $item) {
                            $itemNames[] = $item['name'] . " (" . (($currentLang === 'en') ? "Quantity" : "Số lượng") . ": " . $item['quantity'] . ")";
                        }
                        $itemsStr = implode(", ", $itemNames);
                        
                        $statusMapping = [
                            'pending' => ($currentLang === 'en') ? 'Pending' : 'Chờ xử lý',
                            'processing' => ($currentLang === 'en') ? 'Processing' : 'Đang xử lý',
                            'shipping' => ($currentLang === 'en') ? 'Shipping' : 'Đang giao hàng',
                            'shipped' => ($currentLang === 'en') ? 'Shipped' : 'Đã giao hàng',
                            'delivered' => ($currentLang === 'en') ? 'Delivered' : 'Đã giao hàng',
                            'completed' => ($currentLang === 'en') ? 'Completed' : 'Đã hoàn thành',
                            'cancelled' => ($currentLang === 'en') ? 'Cancelled' : 'Đã hủy'
                        ];
                        
                        $payMapping = [
                            'pending' => ($currentLang === 'en') ? 'Unpaid' : 'Chưa thanh toán',
                            'paid' => ($currentLang === 'en') ? 'Paid' : 'Đã thanh toán',
                            'refunded' => ($currentLang === 'en') ? 'Refunded' : 'Đã hoàn tiền'
                        ];
                        
                        $statusText = $statusMapping[strtolower($oDetail['order_status'])] ?? $oDetail['order_status'];
                        $payText = $payMapping[strtolower($oDetail['payment_status'])] ?? $oDetail['payment_status'];
                        $dateFormatted = date('d/m/Y H:i', strtotime($oDetail['ordered_at']));
                        $totalFormatted = number_format($oDetail['total_amount'], 0, ',', '.') . " VNĐ";
                        
                        $orderContext .= ($currentLang === 'en')
                            ? "- Order #{$orderId}:\n"
                               . "  + Order date: {$dateFormatted}\n"
                               . "  + Total amount: {$totalFormatted}\n"
                               . "  + Order status: {$statusText}\n"
                               . "  + Payment status: {$payText}\n"
                               . "  + Shipping address: {$oDetail['shipping_address']}\n"
                               . "  + Products: {$itemsStr}\n"
                            : "- Đơn hàng #{$orderId}:\n"
                               . "  + Ngày đặt: {$dateFormatted}\n"
                               . "  + Tổng tiền: {$totalFormatted}\n"
                               . "  + Trạng thái đơn hàng: {$statusText}\n"
                               . "  + Trạng thái thanh toán: {$payText}\n"
                               . "  + Địa chỉ giao hàng: {$oDetail['shipping_address']}\n"
                               . "  + Sản phẩm: {$itemsStr}\n";
                    }
                } else {
                    $orderContext = ($currentLang === 'en') ? "The customer has not placed any orders yet.\n" : "Khách hàng chưa có đơn hàng nào tại cửa hàng.\n";
                }
            } else {
                $orderContext = ($currentLang === 'en')
                    ? "The customer is not logged in. Please politely ask the customer to log in to track orders, or use the Order Tracking tool on the website using their Phone Number + Order ID.\n"
                    : "Khách hàng chưa đăng nhập tài khoản. Vui lòng nhắc khách hàng đăng nhập tài khoản để theo dõi đơn hàng, hoặc sử dụng chức năng tra cứu đơn hàng bằng Số điện thoại + Mã đơn hàng trên website.\n";
            }

            $systemPrompt = ($currentLang === 'en')
                ? "You are AN BA TU KHANG - Virtual Customer Care Assistant of TechExpert. 
The customer you are talking to is named: $userName. Always address them politely, friendly, and thoughtfully, personalizing the dialogue using the customer's name (e.g., greet them by name, refer to yourself as 'em' and call them 'anh/chi' if you speak Vietnamese, or use standard polite English pronouns).

INFORMATION ABOUT TECHEXPERT STORE:
- Hotline: 1900-8888 (open from 8:00 to 21:30 daily).
- Address: 123 Ba Thang Hai Street, Ward 11, District 10, Ho Chi Minh City.
- Shipping Policy: 
  + Free shipping nationwide for orders from 10,000,000 VND and above.
  + Orders under 10,000,000 VND are calculated dynamically based on distance to the provinces. Refer to the list below.
  + Delivery time: Ho Chi Minh City inner city on the same day or next day; other provinces in 2-4 working days.
  
$shippingFeesContextEn

- Warranty & Returns Policy:
  + Genuine warranty from 12 to 36 months depending on the component (details according to the accompanying warranty card).
  + 1-to-1 replacement within the first 7 days if there is a hardware manufacturer defect.
  
$orderContext

YOUR MISSION:
1. Answer all customer questions related to ordering, payment, shipping policies, warranty, or contact info politely, briefly, and helpfully.
2. If the customer asks about the shipping fee to a specific province, look up the exact fee in the dynamic shipping fees list and state it clearly.
3. If the customer asks about the status of their orders, use the 'Recent order details' data above to provide exact info on order ID, order date, delivery status, payment status, and shipping address.
4. If the customer is not logged in and wants to ask about their order, politely guide them to log in or use the Order Tracking tool on the website with their Phone Number and Order ID.
5. Avoid answering deep technical questions like building a PC in this mode. If they ask about PC building, guide them to switch to the 'AI CONSULTANT' tab.
6. IMPORTANT NOTE: You must respond in English because the system language is set to English. Only return the direct dialog response of An Ba Tu Khang to the customer. DO NOT generate any analysis, thinking steps, or planning other than the direct response."
                : "Bạn là AN BÁ TỬ KHANG - Nhân viên Chăm sóc Khách hàng ảo của TechExpert. 
Tên khách hàng đang nói chuyện với bạn là: $userName. Hãy luôn xưng hô lễ phép, thân thiện, chu đáo và cá nhân hóa lời thoại theo tên riêng của khách hàng (xưng em và gọi khách là anh/chị nếu biết).

THÔNG TIN VỀ CỬA HÀNG TECHEXPERT:
- Hotline hỗ trợ: 1900-8888 (mở cửa từ 8:00 đến 21:30 hàng ngày).
- Địa chỉ: 123 Đường Ba Tháng Hai, Phường 11, Quận 10, TP. Hồ Chí Minh.
- Chính sách giao hàng: 
  + Miễn phí vận chuyển toàn quốc cho đơn hàng từ 10.000.000 VNĐ trở lên.
  + Đối với đơn hàng dưới 10.000.000 VNĐ, cước phí được tính chi tiết theo bảng giá bên dưới.
  + Thời gian giao hàng: Nội thành TP.HCM trong ngày hoặc ngày hôm sau; tỉnh thành khác từ 2-4 ngày làm việc.

$shippingFeesContextVi

- Chính sách đổi trả & bảo hành:
  + Bảo hành chính hãng từ 12 đến 36 tháng tùy linh kiện (chi tiết theo phiếu bảo hành đi kèm).
  + 1 đổi 1 trong vòng 7 ngày đầu nếu có lỗi phần cứng từ nhà sản xuất.
  
$orderContext

NHIỆM VỤ CỦA BẠN:
1. Trả lời tất cả câu hỏi của khách hàng liên quan đến đặt hàng, thanh toán, chính sách giao hàng, bảo hành hoặc thông tin liên hệ một cách lịch sự, ngắn gọn và hữu ích nhất.
2. Nếu khách hàng hỏi về phí ship của một tỉnh thành cụ thể, hãy tra cứu bảng giá phí vận chuyển động ở trên để trả lời con số chính xác nhất cho khách hàng.
3. Nếu khách hàng hỏi về thông tin hay trạng thái các đơn hàng của họ, hãy dựa vào dữ liệu 'Thông tin đơn hàng gần đây' ở trên để cung cấp thông tin chính xác về mã đơn hàng, ngày đặt, trạng thái giao hàng, trạng thái thanh toán và địa chỉ giao hàng.
4. Nếu khách hàng chưa đăng nhập và muốn hỏi về đơn hàng của mình, hãy lịch sự hướng dẫn khách hàng đăng nhập tài khoản hoặc dùng công cụ Tra cứu đơn hàng trên website bằng Số điện thoại và Mã đơn hàng của họ.
5. Tránh trả lời các vấn đề kỹ thuật sâu như build PC ở chế độ này, nếu khách hàng hỏi build PC, hãy hướng dẫn họ chuyển sang tab 'AI TƯ VẤN' để được hỗ trợ chuyên sâu nhất.
6. CHÚ Ý QUAN TRỌNG: Chỉ trả về câu trả lời thoại trực tiếp của An Bá Tử Khang cho khách hàng bằng tiếng Việt. KHÔNG ĐƯỢC sinh ra bất kỳ văn bản phân tích, các bước suy nghĩ (thinking process) hay các lập kế hoạch phân vai nào khác ngoài câu trả lời trực tiếp.";

        } else {
            // --- Smart Context Retrieval based on User message ---
            $messageClean = mb_strtolower(trim($message));
            $normMessage = $this->removeAccents($messageClean);

            $targetCategoryIds = [];
            
            // Laptop
            if (preg_match('/(laptop|xach tay|notebook|macbook|dell|asus|acer|hp|lenovo|thinkpad)/i', $normMessage)) {
                $targetCategoryIds[] = 1;
            }
            // CPU
            if (preg_match('/(cpu|chip|vi xu ly|intel|amd|ryzen|core|i3|i5|i7|i9)/i', $normMessage)) {
                $targetCategoryIds[] = 5;
                $targetCategoryIds[] = 16;
                $targetCategoryIds[] = 17;
            }
            // RAM
            if (preg_match('/(ram|bo nho trong|ddr4|ddr5)/i', $normMessage)) {
                $targetCategoryIds[] = 6;
            }
            // VGA
            if (preg_match('/(vga|gpu|card do hoa|card man hinh|nvidia|geforce|rtx|gtx|rx|radeon)/i', $normMessage)) {
                $targetCategoryIds[] = 7;
            }
            // Mainboard
            if (preg_match('/(main|mainboard|bo mach chu|h610|b760|z790|b650)/i', $normMessage)) {
                $targetCategoryIds[] = 9;
            }
            // SSD/HDD
            if (preg_match('/(ssd|hdd|o cung|nvme|sata|m2|dung luong)/i', $normMessage)) {
                $targetCategoryIds[] = 10;
                $targetCategoryIds[] = 15;
            }
            // Tản nhiệt
            if (preg_match('/(tan nhiet|cooler|fan|tan nuoc|tan khi)/i', $normMessage)) {
                $targetCategoryIds[] = 11;
            }
            // Vỏ Case
            if (preg_match('/(case|vo may|vo case)/i', $normMessage)) {
                $targetCategoryIds[] = 12;
            }
            // Nguồn (PSU)
            if (preg_match('/(nguon|psu|power supply|cong suat)/i', $normMessage)) {
                $targetCategoryIds[] = 13;
            }
            // Màn hình
            if (preg_match('/(man hinh|monitor|hien thi|hz)/i', $normMessage)) {
                $targetCategoryIds[] = 14;
            }
            // Phụ kiện / Chuột / Bàn phím
            if (preg_match('/(chuot|phim|ban phim|mouse|keyboard|tai nghe|headset|lot chuot|phu kien)/i', $normMessage)) {
                $targetCategoryIds[] = 4;
            }

            if (!empty($targetCategoryIds)) {
                $idsStr = implode(',', array_map('intval', $targetCategoryIds));
                $db->query("SELECT p.id, p.name, p.price, p.main_image, c.name as category 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE p.status = 1 AND p.category_id IN ($idsStr)
                            ORDER BY p.id DESC 
                            LIMIT 35");
            } else {
                $db->query("SELECT p.id, p.name, p.price, p.main_image, c.name as category 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE p.status = 1 AND (p.category_id = 2 OR p.category_id IN (5, 6, 7, 9, 10, 12, 13, 15))
                            ORDER BY p.id DESC 
                            LIMIT 50");
            }
            $products = $db->resultSet();
            
            $baseUrl = URLROOT . "/product/detail/";
            
            $productContext = "Danh sách linh kiện hiện có (COPY NGUYÊN SI PHẦN TRONG NGOẶC VUÔNG):\n";
            if ($products) {
                foreach ($products as $p) {
                    $link = $baseUrl . $p['id'];
                    $img = get_product_image($p['main_image']);
                    $price = number_format($p['price'], 0, ',', '.') . " VNĐ";
                    $cat = $p['category'] ?? 'Linh kiện';
                    // Pre-format exactly as expected in response
                    $productContext .= "- [PRODUCT:{$p['id']}|{$p['name']}|{$price}|{$img}|{$link}] (Loại: $cat)\n";
                }
            }

            $systemPrompt = ($currentLang === 'en')
                ? "You are the PC BUILDING CONSULTANT of TechExpert.
The name of the customer talking to you is: $userName. Address them friendly, personalizing by their name if appropriate.
NOTE: The store has both PRE-BUILT PCs (Desktop Series) and INDIVIDUAL COMPONENTS (CPU, RAM, VGA, Motherboard...).

Here is the list of available products (COPY EXACTLY THE CONTENT INSIDE THE SQUARE BRACKETS):
$productContext

MANDATORY COMPATIBILITY AUDIT RULES:
1. Sockets: An Intel CPU (e.g. LGA1700) must go with an Intel Mainboard. An AMD CPU (e.g. AM5) must go with an AMD Mainboard. Warn the user if they mismatch.
2. RAM type: Mainboard and CPU DDR version (DDR4 vs DDR5) must match the selected RAM kit.
3. Power Supply (PSU): VGA recommended wattage must be satisfied. E.g. RTX 4070 or above needs at least 750W-850W.
4. If the user asks for a configuration, always explain why the components are 100% compatible.

BUDGET OPTIMIZATION & SMART LINKING RULES:
1. Analyze Customer Budget:
   - If the customer specifies a budget (e.g. 15 million VND, 20M, or a price range): Carefully select and combine components whose TOTAL cost is closest to but DOES NOT exceed their budget (allowing a 5-10% buffer below the budget limit).
   - If they search for a single component within a budget (e.g. \"SSD around 1 million VND\"), scan the product list to find SSD/HDD items priced near 1 million and recommend the best options.
2. Smart Markdown Links in Text:
   - When mentioning a specific component in your text explanation, embed its product link using standard Markdown: `[Product Name](Product Link)`. E.g., \"I recommend the [CPU Intel Core i5 12400F](http://localhost/.../product/detail/12) for its excellent price-to-performance ratio...\".
   - The Markdown link URL must match the exact link URL from the corresponding [PRODUCT:...] tag.
3. Interactive Product Cards:
   - Always place the `[PRODUCT:...]` tags of the recommended items at the end of the text, so the frontend UI can render beautiful interactive cards.

MANDATORY CONSULTING RULES:
1. When the customer wants to buy individual parts or Build a PC, select corresponding products (CPU, RAM, VGA...) from the list.
2. Never say that the store does not have individual components.
3. MANDATORY: COPY EXACTLY the [PRODUCT:...] block from the list above (which includes all 5 fields separated by |: [PRODUCT:ID|Name|Price|Image|Link]). Never abbreviate, and do not remove the Image and Link fields at the end. If these 5 fields are not fully present, the product card will fail to display in the UI!
4. STRICTLY FORBIDDEN:
   - Do NOT change the price to 0 VND.
   - Do NOT modify the image link or the product link.
5. Response structure:
   - Consult enthusiastically, explain why you chose those components and explain the compatibility audit check.
   - Embed standard Markdown links in the text when mentioning specific products.
   - List the [PRODUCT:...] tags with full info.
   - Calculate the TOTAL cost at the end.
6. IMPORTANT NOTE: You must respond in English because the system language is set to English. Only return the direct consulting response to the customer. DO NOT generate any analysis, thinking steps, or planning other than the direct response."
                : "Bạn là CHUYÊN GIA TƯ VẤN BUILD PC của TechExpert. 
Tên của khách hàng đang nói chuyện với bạn là: $userName. Hãy xưng hô thân thiện, cá nhân hóa theo tên của họ nếu phù hợp (ví dụ chào tên riêng, xưng hô anh/chị nếu biết).
CHÚ Ý: Cửa hàng có đầy đủ cả MÁY BỘ (Desktop Series) và LINH KIỆN RỜI (CPU, RAM, VGA, Mainboard...).

Dưới đây là danh sách sản phẩm hiện có (COPY CHÍNH XÁC NỘI DUNG TRONG NGOẶC VUÔNG):\n
$productContext\n

QUY TẮC KIỂM TRA ĐỘ TƯƠNG THÍCH BẮT BUỘC:
1. Socket CPU và Mainboard: CPU Intel (LGA1700) bắt buộc đi với Mainboard hỗ trợ LGA1700 (H610, B760, Z790...). CPU AMD (AM5) đi với Mainboard hỗ trợ AM5 (B650, X670...).
2. Chuẩn RAM: Mainboard hỗ trợ DDR4 phải dùng RAM DDR4. Mainboard hỗ trợ DDR5 phải dùng RAM DDR5.
3. Công suất Nguồn (PSU): Đảm bảo PSU đủ công suất gánh VGA. Ví dụ VGA hiệu năng cao cần nguồn tối thiểu 750W-850W.
4. Hãy giải thích ngắn gọn và trực quan lý do tại sao cấu hình bạn đề xuất là 100% tương thích tốt với nhau để tạo sự an tâm cho khách hàng.

QUY TẮC TỐI ƯU HÓA NGÂN SÁCH & LIÊN KẾT THÔNG MINH:
1. Phân tích Ngân sách của Khách hàng:
   - Nếu khách hàng đưa ra ngân sách cụ thể (Ví dụ: 15 triệu, 20 triệu, hoặc khoảng giá): Hãy tính toán và chọn lọc cấu hình linh kiện có TỔNG GIÁ TRỊ sát nhất với ngân sách nhưng KHÔNG VƯỢT QUÁ (được phép dao động dưới ngân sách tầm 5-10%, tránh việc đề xuất cấu hình vượt quá ví tiền của khách hàng).
   - Nếu khách hàng tìm kiếm một linh kiện đơn lẻ (Ví dụ: \"ổ cứng tầm 1 triệu\"), hãy quét danh sách sản phẩm để tìm các sản phẩm thuộc danh mục SSD/HDD có mức giá dao động quanh 1 triệu và gợi ý các tùy chọn tốt nhất.
2. Gợi ý Liên kết Thông minh trong Lời thoại (Markdown Links):
   - Khi tư vấn hoặc nhắc đến một linh kiện cụ thể trong bài phân tích của bạn, hãy lồng ghép link của sản phẩm đó dưới dạng link Markdown chuẩn: `[Tên linh kiện](Đường dẫn liên kết)`. Ví dụ: \"Em đề xuất anh/chị chọn bộ vi xử lý [CPU Intel Core i5 12400F](http://localhost/.../product/detail/12) vì nó có giá rẻ mà hiệu năng cực kỳ tốt...\".
   - Link Markdown này bắt buộc phải khớp chính xác với trường Link trong thẻ [PRODUCT:...] tương ứng.
3. Tích hợp Thẻ sản phẩm [PRODUCT:...]:
   - Luôn đặt các thẻ `[PRODUCT:...]` của các sản phẩm được đề cử ở phần cuối của câu trả lời hoặc sau mỗi phần tư vấn để hệ thống tự động vẽ ra các thẻ card mua hàng tương tác đẹp mắt (khách hàng có thể nhấn mua nhanh trọn bộ hoặc thêm từng linh kiện vào giỏ hàng).

QUY TẮC TƯ VẤN BẮT BUỘC:
1. Khi khách muốn mua linh kiện rời hoặc Build PC, hãy chọn các sản phẩm có loại tương ứng (CPU, RAM, VGA...) từ danh sách.
2. Tuyệt đối không được nói cửa hàng không có linh kiện rời.
3. BẮT BUỘC COPY NGUYÊN SI đoạn [PRODUCT:...] từ danh sách trên (gồm đầy đủ cả 5 trường ngăn cách bởi dấu |: [PRODUCT:ID|Tên|Giá|Ảnh|Link]). Tuyệt đối không được viết tắt, không được tự ý xóa bớt phần Ảnh và Link ở cuối của thẻ. Nếu thiếu đủ 5 trường này, sản phẩm sẽ bị lỗi và không thể hiển thị được trên giao diện của khách hàng!
4. CẤM TUYỆT ĐỐI:
   - KHÔNG được sửa giá thành 0 VNĐ.
   - KHÔNG được sửa link ảnh hay link liên kết sản phẩm.
5. Cấu trúc câu trả lời:
   - Tư vấn nhiệt tình, phân tích tại sao chọn linh kiện đó và đưa ra nhận xét về tính tương thích.
   - Lồng ghép liên kết Markdown của sản phẩm khi đề cập đến trong văn bản giải thích.
   - Liệt kê các thẻ [PRODUCT:...] đầy đủ thông tin để tạo giao diện card sản phẩm trực quan.
   - Tính TỔNG CỘNG chi phí ở cuối.
6. CHÚ Ý QUAN TRỌNG: Chỉ trả về trực tiếp câu trả lời tư vấn cho khách hàng. KHÔNG ĐƯỢC sinh ra bất kỳ văn bản phân tích, các bước suy nghĩ (thinking process) hay các lập kế hoạch phân vai nào khác ngoài câu trả lời trực tiếp.";
        }

        // Lấy lịch sử trò chuyện (tối đa 5 lượt chat = 10 tin nhắn gần nhất) để tạo trí nhớ ngắn hạn
        $history = [];
        if ($userId) {
            // Lấy tối đa 20 bản ghi gần nhất để lọc theo mode thích hợp
            $db->query("SELECT question, answer FROM chat_history WHERE customer_id = :customer_id ORDER BY chatted_at DESC LIMIT 20");
            $db->bind(':customer_id', $userId);
            $rawHistory = $db->resultSet();
            $rawHistory = array_reverse($rawHistory);
            
            $filteredHistory = [];
            foreach ($rawHistory as $chat) {
                $q = $chat['question'];
                $a = $chat['answer'];
                if ($mode === 'shop') {
                    if (strpos($q, '[SHOP] ') === 0) {
                        $filteredHistory[] = [
                            'question' => substr($q, 7),
                            'answer' => strpos($a, '[SHOP] ') === 0 ? substr($a, 7) : $a
                        ];
                    }
                } else {
                    if (strpos($q, '[AI] ') === 0 || strpos($q, '[SHOP] ') !== 0) {
                        $qClean = strpos($q, '[AI] ') === 0 ? substr($q, 5) : $q;
                        $aClean = strpos($a, '[AI] ') === 0 ? substr($a, 5) : $a;
                        $filteredHistory[] = [
                            'question' => $qClean,
                            'answer' => $aClean
                        ];
                    }
                }
            }
            
            // Giới hạn lại 5 lượt chat gần nhất sau khi đã lọc
            $filteredHistory = array_slice($filteredHistory, -5);
            foreach ($filteredHistory as $chat) {
                $history[] = [
                    'role' => 'user',
                    'parts' => [['text' => $chat['question']]]
                ];
                $history[] = [
                    'role' => 'model',
                    'parts' => [['text' => $chat['answer']]]
                ];
            }
        } else {
            $sessionKey = 'chat_history_' . $mode;
            $sessionHistory = $_SESSION[$sessionKey] ?? [];
            $sessionHistory = array_slice($sessionHistory, -5);
            foreach ($sessionHistory as $chat) {
                $history[] = [
                    'role' => 'user',
                    'parts' => [['text' => $chat['question']]]
                ];
                $history[] = [
                    'role' => 'model',
                    'parts' => [['text' => $chat['answer']]]
                ];
            }
        }

        return $gemini->generateResponse($systemPrompt, $history, $message);
    }

    private function findLocalResponse($message) {
        $faqs = $this->chatbotModel->getAllData();
        $messageClean = mb_strtolower(trim($message));
        $normMessage = $this->removeAccents($messageClean);
        
        $bestMatch = null;
        $maxScore = 0;
        
        $stopWordsRaw = [
            'của', 'và', 'là', 'có', 'không', 'thì', 'làm', 'gì', 'bị', 'được', 'cho', 'tôi', 'bạn', 'ở', 'thế', 'nào', 'cả', 'đến', 'đi', 'ra', 'với', 'trong', 'ngoài', 'này', 'kia', 'đó', 'như', 'để', 'tại', 'về', 'cái', 'hay', 'cho', 'nha', 'ạ', 'da', 'dạ',
            'the', 'and', 'are', 'you', 'for', 'how', 'what', 'where', 'when', 'who', 'why', 'can', 'your', 'with', 'this', 'that', 'from', 'about', 'is', 'it', 'to'
        ];
        $stopWords = array_map(function($w) {
            return $this->removeAccents(mb_strtolower($w));
        }, $stopWordsRaw);
        
        foreach ($faqs as $faq) {
            $question = mb_strtolower(trim($faq['question']));
            $normQuestion = $this->removeAccents($question);
            
            // 1. Exact match (with or without accents) gets highest priority
            if ($question === $messageClean || $normQuestion === $normMessage) {
                return $faq['answer'];
            }
            
            // 2. Keyword score matching with boundary check
            $score = 0;
            $keywordsStr = $faq['keywords'] ?? '';
            $keywords = explode(',', $keywordsStr);
            
            foreach ($keywords as $kw) {
                $kw = trim(mb_strtolower($kw));
                if (empty($kw)) continue;
                $normKw = $this->removeAccents($kw);
                
                // Check direct match as whole words
                if (mb_strpos(" " . $normMessage . " ", " " . $normKw . " ") !== false || mb_strpos(" " . $messageClean . " ", " " . $kw . " ") !== false) {
                    $scoreVal = (mb_strlen($normKw) >= 5) ? 15 : 8;
                    $score += $scoreVal;
                }
            }
            
            // 3. Word-by-word matching (excluding stop words)
            $cleanQuestion = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normQuestion);
            $questionWords = explode(' ', $cleanQuestion);
            
            $cleanMessage = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normMessage);
            $messageWords = explode(' ', $cleanMessage);
            
            $matchCount = 0;
            foreach ($messageWords as $word) {
                $word = trim($word);
                if (empty($word) || in_array($word, $stopWords)) continue;
                
                if (mb_strlen($word) > 1 && in_array($word, $questionWords)) {
                    $matchCount++;
                }
            }
            
            $totalScore = $score + ($matchCount * 4);
            if ($totalScore > $maxScore) {
                $maxScore = $totalScore;
                $bestMatch = $faq['answer'];
            }
        }
        
        // Threshold for keyword match (score >= 6)
        if ($maxScore >= 6 && $bestMatch !== null) {
            return $bestMatch;
        }
        
        // Default responses based on language session
        $currentLang = $_SESSION['lang'] ?? 'vi';
        if ($currentLang === 'en') {
            return "Hello! I am a virtual assistant for TechExpert. Since our AI model is currently busy or offline, I can help you with basic questions. What can I do for you?";
        }
        return "Chào bạn! Tôi là trợ lý ảo của TechExpert. Hệ thống AI đang bận hoặc ngoại tuyến, tôi có thể hỗ trợ các câu hỏi cơ bản. Bạn cần giúp gì ạ?";
    }

    private function removeAccents($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    private function findHighConfidenceLocalResponse($message) {
        $faqs = $this->chatbotModel->getAllData();
        $messageClean = mb_strtolower(trim($message));
        $normMessage = $this->removeAccents($messageClean);
        
        $bestMatch = null;
        $maxScore = 0;
        
        $stopWordsRaw = [
            'của', 'và', 'là', 'có', 'không', 'thì', 'làm', 'gì', 'bị', 'được', 'cho', 'tôi', 'bạn', 'ở', 'thế', 'nào', 'cả', 'đến', 'đi', 'ra', 'với', 'trong', 'ngoài', 'này', 'kia', 'đó', 'như', 'để', 'tại', 'về', 'cái', 'hay', 'cho', 'nha', 'ạ', 'da', 'dạ',
            'the', 'and', 'are', 'you', 'for', 'how', 'what', 'where', 'when', 'who', 'why', 'can', 'your', 'with', 'this', 'that', 'from', 'about', 'is', 'it', 'to'
        ];
        $stopWords = array_map(function($w) {
            return $this->removeAccents(mb_strtolower($w));
        }, $stopWordsRaw);
        
        foreach ($faqs as $faq) {
            $question = mb_strtolower(trim($faq['question']));
            $normQuestion = $this->removeAccents($question);
            
            // 1. Exact match gets highest score
            if ($question === $messageClean || $normQuestion === $normMessage) {
                return $faq['answer'];
            }
            
            // 2. Keyword score matching
            $score = 0;
            $keywordsStr = $faq['keywords'] ?? '';
            $keywords = explode(',', $keywordsStr);
            
            foreach ($keywords as $kw) {
                $kw = trim(mb_strtolower($kw));
                if (empty($kw)) continue;
                $normKw = $this->removeAccents($kw);
                
                if (mb_strpos(" " . $normMessage . " ", " " . $normKw . " ") !== false || mb_strpos(" " . $messageClean . " ", " " . $kw . " ") !== false) {
                    $scoreVal = (mb_strlen($normKw) >= 5) ? 15 : 8;
                    $score += $scoreVal;
                }
            }
            
            // 3. Word matching
            $cleanQuestion = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normQuestion);
            $questionWords = explode(' ', $cleanQuestion);
            
            $cleanMessage = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normMessage);
            $messageWords = explode(' ', $cleanMessage);
            
            $matchCount = 0;
            foreach ($messageWords as $word) {
                $word = trim($word);
                if (empty($word) || in_array($word, $stopWords)) continue;
                
                if (mb_strlen($word) > 1 && in_array($word, $questionWords)) {
                    $matchCount++;
                }
            }
            
            $totalScore = $score + ($matchCount * 4);
            if ($totalScore > $maxScore) {
                $maxScore = $totalScore;
                $bestMatch = $faq['answer'];
            }
        }
        
        // High confidence threshold (keyword score >= 20)
        if ($maxScore >= 20 && $bestMatch !== null) {
            return $bestMatch;
        }
        
        return null;
    }

    private function logChat($data) {
        try {
            $db = new Database();
            $db->query("INSERT INTO chat_history (customer_id, question, answer, chatted_at) VALUES (:customer_id, :question, :answer, NOW())");
            $db->bind(':customer_id', $data['customer_id']);
            $db->bind(':question', $data['question']);
            $db->bind(':answer', $data['answer']);
            $db->execute();
        } catch (Exception $e) {}
    }
}
