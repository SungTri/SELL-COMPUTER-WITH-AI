<?php

class NotificationModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getNotificationsByUser($userId) {
        $this->db->query("SELECT * FROM user_notifications WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function markAsRead($id) {
        $this->db->query("UPDATE user_notifications SET is_read = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUnreadCount($userId) {
        $this->db->query("SELECT COUNT(*) as total FROM user_notifications WHERE user_id = :user_id AND is_read = 0");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['total'];
    }

    public function createNotification($userId, $title, $content, $type = 'info') {
        $this->db->query("INSERT INTO user_notifications (user_id, title, content, type) VALUES (:user_id, :title, :content, :type)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', $title);
        $this->db->bind(':content', $content);
        $this->db->bind(':type', $type);
        return $this->db->execute();
    }

    public function deletePromotionByVoucherCode($userId, $code) {
        $this->db->query("DELETE FROM user_notifications WHERE user_id = :user_id AND type = 'promotion' AND (content LIKE :code1 OR title LIKE :code2)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':code1', "%$code%");
        $this->db->bind(':code2', "%$code%");
        return $this->db->execute();
    }

    public function hasPromotionForVoucher($userId, $code) {
        $this->db->query("SELECT id FROM user_notifications WHERE user_id = :user_id AND type = 'promotion' AND (content LIKE :code1 OR title LIKE :code2) LIMIT 1");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':code1', "%$code%");
        $this->db->bind(':code2', "%$code%");
        return $this->db->single() ? true : false;
    }

    /**
     * Lấy danh sách tất cả user_id của khách hàng đã đăng ký
     *
     * @return array
     */
    public function getAllCustomerUserIds() {
        $this->db->query("SELECT id FROM users WHERE role_id = 2 AND status = 1");
        return $this->db->resultSet();
    }

    /**
     * Tạo thông báo cho tất cả khách hàng đã đăng ký tài khoản
     *
     * @param string $title
     * @param string $content
     * @param string $type
     * @return int  số lượng user được gửi thông báo
     */
    public function notifyAllUsers($title, $content, $type = 'new_product') {
        $users = $this->getAllCustomerUserIds();
        $count = 0;
        foreach ($users as $user) {
            $this->createNotification($user['id'], $title, $content, $type);
            $count++;
        }
        return $count;
    }
}

