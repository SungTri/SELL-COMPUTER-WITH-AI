<?php

class SitemapController extends Controller {
    public function index() {
        header("Content-Type: application/xml; charset=utf-8");
        
        $productModel = $this->model('ProductModel');
        $categoryModel = $this->model('CategoryModel');
        
        $products = $productModel->getAllProducts();
        $categories = $categoryModel->getAllCategories();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Home
        $xml .= '<url>';
        $xml .= '<loc>' . URLROOT . '</loc>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';
        
        // Categories
        foreach ($categories as $cat) {
            $xml .= '<url>';
            $xml .= '<loc>' . URLROOT . '/product/category/' . $cat['id'] . '</loc>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }
        
        // Products
        foreach ($products as $prod) {
            $xml .= '<url>';
            $xml .= '<loc>' . URLROOT . '/product/detail/' . $prod['id'] . '</loc>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        echo $xml;
    }
}
