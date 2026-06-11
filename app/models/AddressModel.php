<?php

class AddressModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAddressesByCustomer($customerId) {
        $sql = "SELECT * FROM addresses WHERE customer_id = :customer_id ORDER BY is_default DESC, created_at DESC";
        $this->db->query($sql);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }

    public function addAddress($data) {
        $sql = "INSERT INTO addresses (customer_id, receiver_name, receiver_phone, province, district, ward, address_detail, is_default) 
                VALUES (:customer_id, :receiver_name, :receiver_phone, :province, :district, :ward, :address_detail, :is_default)";
        $this->db->query($sql);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':receiver_name', $data['receiver_name']);
        $this->db->bind(':receiver_phone', $data['receiver_phone']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':ward', $data['ward']);
        $this->db->bind(':address_detail', $data['address_detail']);
        $this->db->bind(':is_default', $data['is_default']);
        return $this->db->execute();
    }

    public function updateAddress($id, $data) {
        $sql = "UPDATE addresses SET receiver_name = :receiver_name, receiver_phone = :receiver_phone, 
                province = :province, district = :district, ward = :ward, address_detail = :address_detail 
                WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':receiver_name', $data['receiver_name']);
        $this->db->bind(':receiver_phone', $data['receiver_phone']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':district', $data['district']);
        $this->db->bind(':ward', $data['ward']);
        $this->db->bind(':address_detail', $data['address_detail']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteAddress($id, $customerId) {
        $sql = "DELETE FROM addresses WHERE id = :id AND customer_id = :customer_id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->execute();
    }

    public function setDefault($id, $customerId) {
        // Reset all to 0
        $sql1 = "UPDATE addresses SET is_default = 0 WHERE customer_id = :customer_id";
        $this->db->query($sql1);
        $this->db->bind(':customer_id', $customerId);
        $this->db->execute();
        
        // Set target to 1
        $sql2 = "UPDATE addresses SET is_default = 1 WHERE id = :id AND customer_id = :customer_id";
        $this->db->query($sql2);
        $this->db->bind(':id', $id);
        $this->db->bind(':customer_id', $customerId);
        return $this->db->execute();
    }
}
