<?php
class CompareController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        if (!isset($_SESSION['compare_list'])) {
            $_SESSION['compare_list'] = [];
        }
    }

    public function toggle($productId) {
        $productId = (int)$productId;
        $index = array_search($productId, $_SESSION['compare_list']);

        if ($index !== false) {
            unset($_SESSION['compare_list'][$index]);
            $_SESSION['compare_list'] = array_values($_SESSION['compare_list']);
            $action = 'removed';
            $message = 'Đã xóa khỏi danh sách so sánh';
        } else {
            if (count($_SESSION['compare_list']) >= 4) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Chỉ có thể so sánh tối đa 4 sản phẩm'
                ]);
                return;
            }
            $_SESSION['compare_list'][] = $productId;
            $action = 'added';
            $message = 'Đã thêm vào danh sách so sánh';
        }

        echo json_encode([
            'status' => 'success',
            'action' => $action,
            'message' => $message,
            'count' => count($_SESSION['compare_list'])
        ]);
    }

    public function getList() {
        $products = [];
        foreach ($_SESSION['compare_list'] as $id) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $products[] = $product;
            }
        }

        echo json_encode([
            'status' => 'success',
            'products' => $products
        ]);
    }

    public function index() {
        $products = [];
        foreach ($_SESSION['compare_list'] as $id) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $products[] = $product;
            }
        }

        $data = [
            'title' => 'So sánh sản phẩm',
            'products' => $products
        ];

        $this->view('products/compare', $data);
    }
}
