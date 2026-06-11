<?php

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserProfile($userId) {
        $sql = "SELECT u.email, c.full_name, c.phone, c.address, c.gender 
                FROM users u 
                LEFT JOIN customers c ON u.id = c.user_id 
                WHERE u.id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $userId);
        return $this->db->single();
    }

    public function updateProfile($userId, $data) {
        $sql = "UPDATE customers SET full_name = :full_name, phone = :phone, address = :address, gender = :gender WHERE user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':gender', $data['gender'] ?? 'Khác');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }


    public function getPassword($userId) {
        $sql = "SELECT password FROM users WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $userId);
        $result = $this->db->single();
        return $result['password'] ?? null;
    }

    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    public function getUserByEmail($email) {
        $this->db->query("SELECT u.*, c.full_name FROM users u LEFT JOIN customers c ON u.id = c.user_id WHERE u.email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function register($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Default role is Customer (2), Status is Active (1)
        $this->db->query("INSERT INTO users (email, password, role_id, status) VALUES (:email, :password, 2, 1)");
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function createCustomerProfile($userId, $fullName, $phone = null) {
        $this->db->query("INSERT INTO customers (user_id, full_name, phone) VALUES (:user_id, :full_name, :phone)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':full_name', $fullName);
        $this->db->bind(':phone', $phone);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getNotifications($userId, $limit = 5) {
        $this->db->query("SELECT * FROM user_notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getUnreadNotificationsCount($userId) {
        $this->db->query("SELECT COUNT(*) as total FROM user_notifications WHERE user_id = :user_id AND is_read = 0");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    public function markNotificationAsRead($userId, $notificationId = null) {
        if ($notificationId) {
            $this->db->query("UPDATE user_notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id");
            $this->db->bind(':id', $notificationId);
        } else {
            $this->db->query("UPDATE user_notifications SET is_read = 1 WHERE user_id = :user_id");
        }
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function saveVoucher($userId, $voucherId) {
        $this->db->query("INSERT IGNORE INTO user_vouchers (user_id, voucher_id) VALUES (:user_id, :voucher_id)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':voucher_id', $voucherId);
        return $this->db->execute();
    }

    public function getSavedVouchers($userId) {
        $this->db->query("
            SELECT v.*, uv.created_at as saved_at, uv.status as usage_status
            FROM user_vouchers uv
            JOIN vouchers v ON uv.voucher_id = v.id
            WHERE uv.user_id = :user_id
            ORDER BY uv.created_at DESC
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function setResetToken($email, $token, $expires = null) {
        // Sử dụng DATE_ADD của MySQL để tránh sai lệch múi giờ giữa PHP và MySQL
        $sql = "UPDATE users SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind(':token', $token);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function getUserByResetToken($token) {
        $sql = "SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW() AND status = 1";
        $this->db->query($sql);
        $this->db->bind(':token', $token);
        return $this->db->single();
    }

    public function updatePasswordWithToken($token, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE reset_token = :token";
        $this->db->query($sql);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }

    public function registerPending($email, $password, $token, $expires) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Default role is Customer (2), Status is Pending (0)
        $this->db->query("INSERT INTO users (email, password, role_id, status, activation_token, token_expires_at) VALUES (:email, :password, 2, 0, :token, :expires)");
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getUserByActivationToken($token) {
        $this->db->query("SELECT * FROM users WHERE activation_token = :token");
        $this->db->bind(':token', $token);
        return $this->db->single();
    }

    public function activateUser($userId) {
        $this->db->query("UPDATE users SET status = 1, activation_token = NULL, token_expires_at = NULL WHERE id = :id");
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
}
