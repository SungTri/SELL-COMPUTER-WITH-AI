<?php

class FeedbackModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Thêm góp ý mới
     */
    public function addFeedback($customerId, $title, $content) {
        $sql = "INSERT INTO feedback (customer_id, title, content, status, submitted_at) 
                VALUES (:customer_id, :title, :content, 0, NOW())";
        
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':title', $title);
        $this->db->bind(':content', $content);
        
        return $this->db->execute();
    }

    /**
     * Lấy danh sách góp ý của một khách hàng cụ thể
     */
    public function getFeedbacksByCustomer($customerId) {
        $sql = "SELECT * FROM feedback 
                WHERE customer_id = :customer_id 
                ORDER BY submitted_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }
}
