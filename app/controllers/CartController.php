<?php

class CartController extends Controller {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel');
        $this->productModel = $this->model('ProductModel');
    }

    public function index() {
        if (isset($_SESSION['customer_id'])) {
            $cart = $this->cartModel->getCartByCustomerId($_SESSION['customer_id']);
            $items = $this->cartModel->getItems($cart['id']);
            
            $_SESSION['cart'] = [];
            foreach ($items as $item) {
                $key = $item['variant_id'] ? $item['product_id'] . '_' . $item['variant_id'] : $item['product_id'];
                $_SESSION['cart'][$key] = [
                    'id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'name' => $item['name'],
                    'specs' => $item['variant_name'] ? $item['variant_name'] : $item['specs'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image']
                ];
            }
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $data = [
            'title' => 'Giỏ hàng của bạn - TechExpert Store',
            'noindex' => true,
            'cart_items' => $_SESSION['cart'],
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'shipping' => 0,
            'total' => number_format($subtotal, 0, ',', '.'),
            'cart_count' => count($_SESSION['cart'])
        ];

        $this->view('cart/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $variant_id = !empty($_POST['variant_id']) ? intval($_POST['variant_id']) : null;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

            $product = $this->productModel->getProductById($product_id);

            if ($product) {
                $cart_key = $variant_id ? $product_id . '_' . $variant_id : $product_id;
                
                // Real-time Stock Check
                $currentInCart = 0;
                if (isset($_SESSION['cart'][$cart_key])) {
                    $currentInCart = $_SESSION['cart'][$cart_key]['quantity'];
                }

                $stockCheck = $this->productModel->checkStock($product_id, $currentInCart + $quantity, $variant_id);
                if ($stockCheck['status'] !== 'ok') {
                    $available = isset($stockCheck['available']) ? $stockCheck['available'] : 0;
                    $msg = "Rất tiếc, chỉ còn {$available} sản phẩm trong kho.";
                    if ($available <= 0) $msg = "Sản phẩm này đã hết hàng.";
                    
                    if ($this->isAjax()) {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => $msg]);
                        return;
                    }
                    $_SESSION['error'] = $msg;
                    header('location: ' . URLROOT . '/product/detail/' . $product_id);
                    return;
                }

                // Ensure session cart exists
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $price = $product['price'];
                $specs = $product['short_description'];
                $image = $product['main_image'];
                if ($variant_id) {
                    $variant = $this->productModel->getVariantById($variant_id);
                    if ($variant) {
                        $price = $variant['price'];
                        $specs = $variant['variant_name'] ? $variant['variant_name'] : $specs;
                        if (!empty($variant['image'])) {
                            $image = $variant['image'];
                        }
                    }
                }

                // Update session cart first (always works for both guests and members)
                if (isset($_SESSION['cart'][$cart_key])) {
                    $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$cart_key] = [
                        'id' => $product['id'],
                        'variant_id' => $variant_id,
                        'name' => $product['name'],
                        'specs' => $specs,
                        'price' => $price,
                        'quantity' => $quantity,
                        'image' => $image
                    ];
                }

                // Sync to DB in background if logged in
                if (isset($_SESSION['customer_id'])) {
                    try {
                        $cart = $this->cartModel->getCartByCustomerId($_SESSION['customer_id']);
                        if ($cart) {
                            $this->cartModel->addItem($cart['id'], $product_id, $quantity, $variant_id);
                        }
                    } catch (Exception $e) {
                        // Silent fail for DB sync to keep guest experience smooth
                    }
                }

                // Support AJAX
                if ($this->isAjax()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'success',
                        'cart_count' => count($_SESSION['cart']),
                        'message' => 'Đã thêm vào giỏ hàng'
                    ]);
                    return;
                }

                if (isset($_POST['action']) && $_POST['action'] == 'buy') {
                    header('location: ' . URLROOT . '/checkout');
                } else {
                    header('location: ' . URLROOT . '/cart');
                }
                return;
            }
        }
        
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm vào giỏ hàng']);
            return;
        }
        header('location: ' . URLROOT);
    }

    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $qty = max(1, intval($_POST['quantity']));

            $parts = explode('_', $id);
            $product_id = intval($parts[0]);
            $variant_id = isset($parts[1]) ? intval($parts[1]) : null;

            $stockCheck = $this->productModel->checkStock($product_id, $qty, $variant_id);
            if ($stockCheck['status'] !== 'ok') {
                $available = isset($stockCheck['available']) ? $stockCheck['available'] : 0;
                echo json_encode([
                    'status' => 'error', 
                    'message' => "Chỉ còn {$available} sản phẩm trong kho.",
                    'max_qty' => $available
                ]);
                return;
            }
            
            if (isset($_SESSION['customer_id'])) {
                $cart = $this->cartModel->getCartByCustomerId($_SESSION['customer_id']);
                $this->cartModel->updateQuantity($cart['id'], $product_id, $qty, $variant_id);
            }

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $qty;
                echo json_encode(['status' => 'success']);
                return;
            }
        }
        echo json_encode(['status' => 'error']);
    }

    public function emptyCart() {
        // Clear DB cart if logged in
        if (isset($_SESSION['customer_id'])) {
            $cart = $this->cartModel->getCartByCustomerId($_SESSION['customer_id']);
            if ($cart) {
                $this->cartModel->clearCart($cart['id']);
            }
        }

        // Clear session cart
        $_SESSION['cart'] = [];
        unset($_SESSION['cart']);
        $_SESSION['cart'] = [];
        
        // Redirect back to cart page
        header('location: ' . URLROOT . '/cart');
        exit();
    }

    public function bulkAdd() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $product_ids = $data['product_ids'] ?? [];

            if (empty($product_ids)) {
                echo json_encode(['status' => 'error', 'message' => 'Danh sách sản phẩm trống']);
                return;
            }

            $count = 0;
            // Ensure session cart exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            foreach ($product_ids as $item_id) {
                $parts = explode('_', $item_id);
                $product_id = intval($parts[0]);
                $variant_id = isset($parts[1]) ? intval($parts[1]) : null;

                $product = $this->productModel->getProductById($product_id);
                if ($product) {
                    $cart_key = $variant_id ? $product_id . '_' . $variant_id : $product_id;
                    $price = $product['price'];
                    $specs = $product['short_description'];
                    $image = $product['main_image'];

                    if ($variant_id) {
                        $variant = $this->productModel->getVariantById($variant_id);
                        if ($variant) {
                            $price = $variant['price'];
                            $specs = $variant['variant_name'] ? $variant['variant_name'] : $specs;
                            if (!empty($variant['image'])) {
                                $image = $variant['image'];
                            }
                        }
                    }

                    // Update session cart first
                    if (isset($_SESSION['cart'][$cart_key])) {
                        $_SESSION['cart'][$cart_key]['quantity'] += 1;
                    } else {
                        $_SESSION['cart'][$cart_key] = [
                            'id' => $product['id'],
                            'variant_id' => $variant_id,
                            'name' => $product['name'],
                            'specs' => $specs,
                            'price' => $price,
                            'quantity' => 1,
                            'image' => $image
                        ];
                    }

                    // Sync to DB if logged in
                    if (isset($_SESSION['customer_id'])) {
                        try {
                            $cart = $this->cartModel->getCartByCustomerId($_SESSION['customer_id']);
                            if ($cart) {
                                $this->cartModel->addItem($cart['id'], $product_id, 1, $variant_id);
                            }
                        } catch (Exception $e) {
                            // Silent fail for DB sync
                        }
                    }
                    $count++;
                }
            }

            echo json_encode([
                'status' => 'success',
                'count' => $count,
                'cart_count' => count($_SESSION['cart']),
                'message' => "Đã thêm $count sản phẩm vào giỏ hàng"
            ]);
            return;
        }
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    }

    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function removeItem() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            $parts = explode('_', $id);
            $product_id = intval($parts[0]);
            $variant_id = isset($parts[1]) ? intval($parts[1]) : null;

            if (isset($_SESSION['customer_id'])) {
                $cart = $this->cartModel->getCartByCustomerId($_SESSION['customer_id']);
                $this->cartModel->removeItem($cart['id'], $product_id, $variant_id);
            }

            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                echo json_encode(['status' => 'success', 'cart_count' => count($_SESSION['cart'])]);
                return;
            }
        }
        echo json_encode(['status' => 'error']);
    }
}
