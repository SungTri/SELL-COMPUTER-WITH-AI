<?php

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCategories() {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query("SELECT c.*, p.name as parent_name, p.id as parent_id 
                         FROM categories c 
                         LEFT JOIN categories p ON c.parent_id = p.id 
                         WHERE c.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getCategoriesWithBrands() {
        // Only get TOP LEVEL categories
        $this->db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY id ASC");
        $categories = $this->db->resultSet();
        
        foreach($categories as &$category) {
            // Get subcategories
            $this->db->query("SELECT * FROM categories WHERE parent_id = :parent_id ORDER BY name ASC");
            $this->db->bind(':parent_id', $category['id']);
            $category['subcategories'] = $this->db->resultSet();

            // Get brands
            $this->db->query("SELECT DISTINCT b.* 
                             FROM brands b 
                             JOIN products p ON b.id = p.brand_id 
                             WHERE p.category_id = :category_id OR p.category_id IN (SELECT id FROM categories WHERE parent_id = :category_id)");
            $this->db->bind(':category_id', $category['id']);
            $category['brands'] = $this->db->resultSet();
        }
        
        return $categories;
    }
}
