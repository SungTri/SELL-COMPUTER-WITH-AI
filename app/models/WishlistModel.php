<?php

class WishlistModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getWishlistByCustomer($customerId) {
        $sql = "SELECT p.id, p.name, p.price, p.main_image, w.added_at, b.name as brand_name 
                FROM wishlist w 
                JOIN products p ON w.product_id = p.id 
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE w.customer_id = :customer_id 
                ORDER BY w.added_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }

    public function addToWishlist($customerId, $productId) {
        $sql = "INSERT IGNORE INTO wishlist (customer_id, product_id) VALUES (:customer_id, :product_id)";
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function removeFromWishlist($customerId, $productId) {
        $sql = "DELETE FROM wishlist WHERE customer_id = :customer_id AND product_id = :product_id";
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function isInWishlist($customerId, $productId) {
        $sql = "SELECT COUNT(*) as total FROM wishlist WHERE customer_id = :customer_id AND product_id = :product_id";
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        $result = $this->db->single();
        return $result['total'] > 0;
    }
}
