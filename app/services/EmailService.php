<?php
/**
 * Email Service using Brevo REST API or Test Log fallback
 */

class EmailService {
    /**
     * Send activation email with secure link to user
     * 
     * @param string $toEmail
     * @param string $toName
     * @param string $activationLink
     * @return bool
     */
    public function sendActivationEmail($toEmail, $toName, $activationLink) {
        $subject = 'Kích hoạt tài khoản TechExpert';
        $htmlContent = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e2e4; border-radius: 12px; background-color: #ffffff;">
            <h2 style="color: #0453cd; margin-bottom: 20px; text-align: center;">Kích Hoạt Tài Khoản TechExpert</h2>
            <p>Xin chào <strong>' . htmlspecialchars($toName) . '</strong>,</p>
            <p>Cảm ơn bạn đã đăng ký thành viên tại TechExpert. Vui lòng bấm vào nút dưới đây để kích hoạt tài khoản của bạn:</p>
            <p style="margin: 30px 0; text-align: center;">
                <a href="' . $activationLink . '" style="background-color: #0453cd; color: #ffffff; padding: 12px 28px; text-decoration: none; font-weight: bold; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(4,83,205,0.2);">KÍCH HOẠT TÀI KHOẢN</a>
            </p>
            <p style="color: #46474a; font-size: 13px;">Liên kết này có hiệu lực trong vòng 24 giờ. Nếu nút trên không hoạt động, bạn có thể sao chép liên kết dưới đây và dán vào trình duyệt:</p>
            <p style="word-break: break-all; font-size: 13px; color: #0453cd; background-color: #f3f4f6; padding: 10px; border-radius: 6px;">' . $activationLink . '</p>
            <hr style="border: none; border-top: 1px solid #e1e2e4; margin: 25px 0;">
            <p style="color: #909090; font-size: 12px; text-align: center;">Đây là email tự động từ hệ thống TechExpert, vui lòng không trả lời thư này.</p>
        </div>
        ';

        // Check if we are in test mode
        if (defined('BREVO_API_KEY') && strpos(BREVO_API_KEY, '-testmode') !== false) {
            // Write to a local log file inside storage for easy developer testing
            $logDir = ROOT . '/storage';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/email_log.txt';
            $logContent = "=== [EMAIL LOG - " . date('Y-m-d H:i:s') . "] ===\n";
            $logContent .= "To: {$toName} <{$toEmail}>\n";
            $logContent .= "Subject: {$subject}\n";
            $logContent .= "Activation Link: {$activationLink}\n";
            $logContent .= "=======================================\n\n";
            file_put_contents($logFile, $logContent, FILE_APPEND);
            return true;
        }

        // Real Brevo API Request
        $url = 'https://api.brevo.com/v3/smtp/email';
        $data = [
            'sender' => [
                'name' => SENDER_NAME,
                'email' => SENDER_EMAIL
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName
                ]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . BREVO_API_KEY,
            'content-type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 201 || $httpCode === 200;
    }

    /**
     * Gửi email xác nhận đặt hàng thành công
     * 
     * @param string $toEmail
     * @param string $toName
     * @param array $order
     * @param array $items
     * @return bool
     */
    public function sendOrderConfirmationEmail($toEmail, $toName, $order, $items) {
        $subject = 'Xác nhận đơn hàng #' . $order['id'] . ' thành công tại TechExpert';
        
        $itemsHtml = '';
        foreach ($items as $item) {
            $price = $item['price_at_purchase'] ?? $item['price'];
            $itemsHtml .= '
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <td style="padding: 10px 0; color: #4b5563;">' . htmlspecialchars($item['name']) . '</td>
                <td style="padding: 10px 0; color: #4b5563; text-align: center;">' . htmlspecialchars($item['quantity']) . '</td>
                <td style="padding: 10px 0; color: #1f2937; text-align: right; font-weight: bold;">' . number_format($price * $item['quantity'], 0, ',', '.') . 'đ</td>
            </tr>';
        }

        $htmlContent = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e2e4; border-radius: 12px; background-color: #ffffff;">
            <div style="text-align: center; padding-bottom: 20px; border-bottom: 2px solid #f3f4f6;">
                <h2 style="color: #0453cd; margin: 0; font-size: 24px;">Xác Nhận Đơn Hàng Thành Công</h2>
                <p style="color: #6b7280; font-size: 14px; margin: 5px 0 0 0;">Cảm ơn bạn đã mua sắm tại TechExpert</p>
            </div>
            
            <div style="padding: 20px 0;">
                <p>Xin chào <strong>' . htmlspecialchars($toName) . '</strong>,</p>
                <p>TechExpert đã tiếp nhận đơn hàng của bạn và đang tiến hành xử lý. Dưới đây là thông tin chi tiết đơn hàng:</p>
                
                <div style="background-color: #f8f9fa; border-radius: 8px; padding: 15px; margin: 20px 0;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tr>
                            <td style="padding: 5px 0; color: #6b7280; width: 40%;">Mã đơn hàng:</td>
                            <td style="padding: 5px 0; font-weight: bold; color: #1f2937;">#' . htmlspecialchars($order['id']) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; color: #6b7280;">Phương thức thanh toán:</td>
                            <td style="padding: 5px 0; font-weight: bold; color: #1f2937;">' . htmlspecialchars($order['payment_method']) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; color: #6b7280;">Địa chỉ giao hàng:</td>
                            <td style="padding: 5px 0; font-weight: bold; color: #1f2937;">' . htmlspecialchars($order['shipping_address']) . '</td>
                        </tr>
                    </table>
                </div>
                
                <h3 style="color: #1f2937; font-size: 16px; margin: 20px 0 10px 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px;">Sản phẩm đặt mua</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                            <th style="padding: 8px 0; color: #374151;">Sản phẩm</th>
                            <th style="padding: 8px 0; color: #374151; text-align: center; width: 15%;">SL</th>
                            <th style="padding: 8px 0; color: #374151; text-align: right; width: 25%;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $itemsHtml . '
                    </tbody>
                </table>
                
                <div style="border-top: 2px solid #e5e7eb; padding-top: 10px; margin-top: 10px; text-align: right; font-size: 14px;">
                    <p style="margin: 5px 0; color: #4b5563;">Giảm giá: <strong style="color: #dc2626;">-' . number_format($order['discount_amount'] ?? 0, 0, ',', '.') . 'đ</strong></p>
                    <p style="margin: 5px 0; color: #1f2937; font-size: 16px;">Tổng cộng: <strong style="color: #0453cd; font-size: 18px;">' . number_format($order['total_amount'], 0, ',', '.') . 'đ</strong></p>
                </div>
                
                <div style="text-align: center; margin: 30px 0 10px 0;">
                    <a href="' . URLROOT . '/user/profile?tab=orders" style="background-color: #0453cd; color: #ffffff; padding: 12px 28px; text-decoration: none; font-weight: bold; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(4,83,205,0.2);">Xem Chi Tiết Đơn Hàng</a>
                </div>
            </div>
            
            <hr style="border: none; border-top: 1px solid #e1e2e4; margin: 25px 0;">
            <p style="color: #909090; font-size: 12px; text-align: center;">Đây là email tự động từ hệ thống TechExpert. Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ bộ phận hỗ trợ.</p>
        </div>
        ';

        // Check if we are in test mode
        if (defined('BREVO_API_KEY') && strpos(BREVO_API_KEY, '-testmode') !== false) {
            // Write to a local log file inside storage for easy developer testing
            $logDir = ROOT . '/storage';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/email_log.txt';
            $logContent = "=== [ORDER EMAIL LOG - " . date('Y-m-d H:i:s') . "] ===\n";
            $logContent .= "To: {$toName} <{$toEmail}>\n";
            $logContent .= "Subject: {$subject}\n";
            $logContent .= "Order Details:\n";
            $logContent .= " - Order ID: #{$order['id']}\n";
            $logContent .= " - Total Amount: " . number_format($order['total_amount'], 0, ',', '.') . "đ\n";
            $logContent .= " - Payment Method: {$order['payment_method']}\n";
            $logContent .= " - Shipping Address: {$order['shipping_address']}\n";
            $logContent .= "Items:\n";
            foreach ($items as $item) {
                $price = $item['price_at_purchase'] ?? $item['price'];
                $logContent .= "   * {$item['name']} x{$item['quantity']} - " . number_format($price, 0, ',', '.') . "đ\n";
            }
            $logContent .= "=======================================\n\n";
            file_put_contents($logFile, $logContent, FILE_APPEND);
            return true;
        }

        // Real Brevo API Request
        $url = 'https://api.brevo.com/v3/smtp/email';
        $data = [
            'sender' => [
                'name' => SENDER_NAME,
                'email' => SENDER_EMAIL
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName
                ]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . BREVO_API_KEY,
            'content-type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 201 || $httpCode === 200;
    }

    /**
     * Gửi email thông báo sản phẩm mới đến danh sách subscriber
     *
     * @param string $toEmail
     * @param string $productName
     * @param string $productDescription
     * @param string $productPrice
     * @param string $productImage
     * @param string $productUrl
     * @return bool
     */
    public function sendNewProductEmail($toEmail, $productName, $productDescription, $productPrice, $productImage, $productUrl) {
        $subject = '🎉 Sản phẩm mới tại TechExpert: ' . $productName;

        $imageHtml = !empty($productImage)
            ? '<img src="' . htmlspecialchars($productImage) . '" alt="' . htmlspecialchars($productName) . '" style="max-width:100%; border-radius:8px; margin-bottom:16px;">'
            : '';

        $htmlContent = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e2e4; border-radius: 12px; background-color: #ffffff;">
            <div style="text-align: center; padding-bottom: 20px; border-bottom: 2px solid #f3f4f6;">
                <h2 style="color: #0453cd; margin: 0; font-size: 24px;">🎉 Sản Phẩm Mới Tại TechExpert</h2>
                <p style="color: #6b7280; font-size: 14px; margin: 6px 0 0 0;">Khám phá ngay sản phẩm mới nhất vừa được cập nhật!</p>
            </div>

            <div style="padding: 24px 0;">
                ' . $imageHtml . '
                <h3 style="color: #1f2937; font-size: 20px; margin: 0 0 10px 0;">' . htmlspecialchars($productName) . '</h3>
                <p style="color: #4b5563; font-size: 14px; margin: 0 0 16px 0; line-height: 1.6;">' . htmlspecialchars($productDescription) . '</p>
                <p style="margin: 0 0 24px 0;">
                    <span style="font-size: 22px; font-weight: bold; color: #0453cd;">' . $productPrice . '</span>
                </p>
                <div style="text-align: center;">
                    <a href="' . $productUrl . '" style="background-color: #0453cd; color: #ffffff; padding: 13px 32px; text-decoration: none; font-weight: bold; border-radius: 8px; display: inline-block; font-size: 15px; box-shadow: 0 4px 6px rgba(4,83,205,0.2);">
                        Xem Sản Phẩm Ngay
                    </a>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #e1e2e4; margin: 20px 0;">
            <p style="color: #909090; font-size: 12px; text-align: center;">Bạn nhận được email này vì đã đăng ký nhận bản tin từ TechExpert.<br>Đây là email tự động, vui lòng không trả lời thư này.</p>
        </div>
        ';

        // Test mode: ghi vào log file
        if (defined('BREVO_API_KEY') && strpos(BREVO_API_KEY, '-testmode') !== false) {
            $logDir = ROOT . '/storage';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/email_log.txt';
            $logContent  = "=== [NEW PRODUCT EMAIL LOG - " . date('Y-m-d H:i:s') . "] ===\n";
            $logContent .= "To: <{$toEmail}>\n";
            $logContent .= "Subject: {$subject}\n";
            $logContent .= "Product: {$productName} | Price: {$productPrice}\n";
            $logContent .= "URL: {$productUrl}\n";
            $logContent .= "=======================================\n\n";
            file_put_contents($logFile, $logContent, FILE_APPEND);
            return true;
        }

        // Gửi thật qua Brevo API
        $url = 'https://api.brevo.com/v3/smtp/email';
        $data = [
            'sender' => [
                'name'  => SENDER_NAME,
                'email' => SENDER_EMAIL
            ],
            'to' => [
                ['email' => $toEmail]
            ],
            'subject'     => $subject,
            'htmlContent' => $htmlContent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . BREVO_API_KEY,
            'content-type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 201 || $httpCode === 200;
    }

    /**
     * Send new password email to user
     * 
     * @param string $toEmail
     * @param string $toName
     * @param string $newPassword
     * @return bool
     */
    public function sendNewPasswordEmail($toEmail, $toName, $newPassword) {
        $subject = 'Mật khẩu mới của tài khoản TechExpert';
        $htmlContent = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e2e4; border-radius: 12px; background-color: #ffffff;">
            <h2 style="color: #0453cd; margin-bottom: 20px; text-align: center;">Mật Khẩu Mới - TechExpert</h2>
            <p>Xin chào <strong>' . htmlspecialchars($toName) . '</strong>,</p>
            <p>Chúng tôi đã nhận được yêu cầu cấp lại mật khẩu của bạn. Mật khẩu mới truy cập của bạn đã được thiết lập lại:</p>
            <div style="margin: 30px 0; text-align: center; background-color: #f3f4f6; padding: 15px; border-radius: 8px; border: 1px dashed #0453cd;">
                <span style="font-size: 24px; font-weight: bold; color: #ba1a1a; letter-spacing: 2px; font-family: monospace;">' . htmlspecialchars($newPassword) . '</span>
            </div>
            <p>Vui lòng đăng nhập bằng mật khẩu này và đổi lại mật khẩu mới trong phần trang cá nhân để đảm bảo an toàn.</p>
            <p style="margin: 25px 0; text-align: center;">
                <a href="' . URLROOT . '/auth/login" style="background-color: #0453cd; color: #ffffff; padding: 12px 28px; text-decoration: none; font-weight: bold; border-radius: 8px; display: inline-block;">ĐĂNG NHẬP NGAY</a>
            </p>
            <hr style="border: none; border-top: 1px solid #e1e2e4; margin: 25px 0;">
            <p style="color: #909090; font-size: 12px; text-align: center;">Đây là email tự động từ hệ thống TechExpert, vui lòng không trả lời thư này.</p>
        </div>
        ';

        if (defined('BREVO_API_KEY') && strpos(BREVO_API_KEY, '-testmode') !== false) {
            $logDir = ROOT . '/storage';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/email_log.txt';
            $logContent = "=== [PASSWORD RESET EMAIL LOG - " . date('Y-m-d H:i:s') . "] ===\n";
            $logContent .= "To: {$toName} <{$toEmail}>\n";
            $logContent .= "Subject: {$subject}\n";
            $logContent .= "New Password: {$newPassword}\n";
            $logContent .= "=======================================\n\n";
            file_put_contents($logFile, $logContent, FILE_APPEND);
            return true;
        }

        $url = 'https://api.brevo.com/v3/smtp/email';
        $data = [
            'sender' => [
                'name' => SENDER_NAME,
                'email' => SENDER_EMAIL
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName
                ]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . BREVO_API_KEY,
            'content-type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 201 || $httpCode === 200;
    }
}

