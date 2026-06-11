<?php

class SystemModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getSettings() {
        $this->db->query("SELECT * FROM settings");
        $results = $this->db->resultSet();
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
}
