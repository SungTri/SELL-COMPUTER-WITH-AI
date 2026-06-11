<?php

class RecommendController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->categoryModel = $this->model('CategoryModel');
    }

    public function build() {
        // Define components needed for a build
        $components = [
            ['id' => 'cpu', 'name' => __('CPU'), 'category_id' => 5],
            ['id' => 'mainboard', 'name' => __('Mainboard'), 'category_id' => 9],
            ['id' => 'ram', 'name' => __('RAM'), 'category_id' => 6],
            ['id' => 'vga', 'name' => __('VGA (Card đồ họa)'), 'category_id' => 7],
            ['id' => 'storage', 'name' => __('Ổ cứng (SSD/HDD)'), 'category_id' => 10],
            ['id' => 'psu', 'name' => __('Nguồn (PSU)'), 'category_id' => 13],
            ['id' => 'case', 'name' => __('Vỏ máy (Case)'), 'category_id' => 12],
            ['id' => 'cooler', 'name' => __('Tản nhiệt'), 'category_id' => 11],
            ['id' => 'monitor', 'name' => __('Màn hình'), 'category_id' => 14],
        ];

        // Fetch products for each component if requested via AJAX or just pass IDs
        $data = [
            'title' => __('build_pc_title', 'Build Your Dream Setup'),
            'components' => $components
        ];

        $this->view('recommend/build', $data);
    }

    public function getProductsByCategory($categoryId) {
        $products = $this->productModel->getProductsByCategory($categoryId);
        header('Content-Type: application/json');
        echo json_encode($products);
    }
}
