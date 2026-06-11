<?php

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getOrdersByCustomer($customerId) {
        $sql = "SELECT id, ordered_at as date, total_amount as total, order_status as status 
                FROM orders 
                WHERE customer_id = :customer_id 
                ORDER BY ordered_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }

    public function createOrder($customerId, $totalAmount, $paymentMethod, $shippingAddress, $voucherId = null, $discountAmount = 0, $shippingFee = 0) {
        $sql = "INSERT INTO orders (customer_id, total_amount, shipping_fee, discount_amount, payment_method, payment_status, order_status, shipping_address, voucher_id) 
                VALUES (:customer_id, :total_amount, :shipping_fee, :discount_amount, :payment_method, 'pending', 'pending', :shipping_address, :voucher_id)";
        
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':total_amount', $totalAmount);
        $this->db->bind(':shipping_fee', $shippingFee);
        $this->db->bind(':discount_amount', $discountAmount);
        $this->db->bind(':payment_method', $paymentMethod);
        $this->db->bind(':shipping_address', $shippingAddress);
        $this->db->bind(':voucher_id', $voucherId);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addOrderItems($orderId, $cartItems) {
        $sql = "INSERT INTO order_items (order_id, product_id, variant_id, variant_name, quantity, price_at_purchase) 
                VALUES (:order_id, :product_id, :variant_id, :variant_name, :quantity, :price_at_purchase)";
        
        $this->db->query($sql);
        
        foreach ($cartItems as $item) {
            $this->db->bind(':order_id', $orderId);
            $productId = isset($item['product_id']) ? $item['product_id'] : $item['id'];
            $this->db->bind(':product_id', $productId);
            
            $variantId = isset($item['variant_id']) ? $item['variant_id'] : null;
            $this->db->bind(':variant_id', $variantId);
            
            $variantName = isset($item['variant_name']) ? $item['variant_name'] : ($item['specs'] ?? null);
            if (!$variantId) {
                $variantName = null;
            }
            $this->db->bind(':variant_name', $variantName);
            
            $this->db->bind(':quantity', $item['quantity']);
            $this->db->bind(':price_at_purchase', $item['price']);
            $this->db->execute();
        }
        return true;
    }

    public function getOrderById($orderId) {
        $this->db->query("
            SELECT o.*, c.full_name, c.phone, u.email, c.user_id 
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE o.id = :id
        ");
        $this->db->bind(':id', $orderId);
        return $this->db->single();
    }

    public function getOrderItems($orderId) {
        $this->db->query("
            SELECT oi.*, p.name, COALESCE(pv.image, p.main_image) as image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE oi.order_id = :order_id
        ");
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function cancelOrder($orderId, $customerId) {
        $this->db->query("UPDATE orders SET order_status = 'cancelled' WHERE id = :id AND customer_id = :customer_id AND order_status = 'pending'");
        $this->db->bind(':id', $orderId);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->execute() && $this->db->rowCount() > 0;
    }

    public function getOrderByTracking($orderId, $phone) {
        $this->db->query("
            SELECT o.*, c.full_name, c.phone 
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            WHERE o.id = :id AND c.phone = :phone
        ");
        $this->db->bind(':id', $orderId);
        $this->db->bind(':phone', $phone);
        return $this->db->single();
    }

    public function getOrderStatusLogs($orderId) {
        $this->db->query("SELECT * FROM order_status_logs WHERE order_id = :order_id ORDER BY created_at DESC");
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function logBankTransaction($data) {
        $this->db->query("INSERT INTO bank_transactions (transaction_id, amount, description, order_id, status) 
                         VALUES (:transaction_id, :amount, :description, :order_id, :status)");
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':order_id', $data['order_id']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function updatePaymentStatus($orderId, $status) {
        $this->db->query("UPDATE orders SET payment_status = :status, order_status = 'processing' WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $orderId);
        
        if ($this->db->execute()) {
            $statusText = strtolower($status) === 'paid' ? 'Đã thanh toán' : $status;
            $this->addOrderStatusLog($orderId, $statusText, 'Hệ thống đã tự động xác nhận thanh toán thành công qua ngân hàng.');
            return true;
        }
        return false;
    }

    public function addOrderStatusLog($orderId, $status, $description) {
        $this->db->query("INSERT INTO order_status_logs (order_id, status, description) VALUES (:order_id, :status, :description)");
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':status', $status);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    public function isTransactionProcessed($transactionId) {
        $this->db->query("SELECT id FROM bank_transactions WHERE transaction_id = :tid AND status = 'Success'");
        $this->db->bind(':tid', $transactionId);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function getShippingFeeByProvince($provinceName) {
        if (empty($provinceName)) {
            return 30000.00;
        }
        
        // Chuẩn hóa tên tỉnh để so sánh an toàn bằng cách bỏ tiền tố "Tỉnh ", "Thành phố "
        $this->db->query("
            SELECT shipping_fee FROM provinces_shipping 
            WHERE LOWER(REPLACE(REPLACE(province_name, 'Thành phố ', ''), 'Tỉnh ', '')) = LOWER(REPLACE(REPLACE(:name, 'Thành phố ', ''), 'Tỉnh ', ''))
            LIMIT 1
        ");
        $this->db->bind(':name', trim($provinceName));
        $row = $this->db->single();
        return $row ? (float)$row['shipping_fee'] : 30000.00;
    }

    public function getAllShippingFees() {
        $this->db->query("SELECT province_name, shipping_fee FROM provinces_shipping");
        return $this->db->resultSet();
    }
}
