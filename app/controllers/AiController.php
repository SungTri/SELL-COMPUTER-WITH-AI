<?php

class AiController extends Controller {
    private $productModel;
    private $apiKey = GEMINI_API_KEY; 

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
    }

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT);
            exit();
        }

        $query = trim($_POST['query'] ?? '');
        if (empty($query)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập nhu cầu của bạn.']);
            exit();
        }

        try {
            $recommendations = $this->getAiRecommendations($query);
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'data' => $recommendations
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi AI: ' . $e->getMessage()]);
        }
        exit();
    }

    private function getAiRecommendations($userQuery) {
        $gemini = new GeminiService($this->apiKey);

        // Fetch all active products for context
        $db = new Database();
        $db->query("SELECT p.id, p.name, p.price, p.main_image, c.name as category 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.status = 1
                    ORDER BY p.id DESC");
        $products = $db->resultSet();

        $productContext = "";
        foreach ($products as $p) {
            $price = number_format($p['price'], 0, ',', '.') . " VNĐ";
            $productContext .= "- ID: {$p['id']} | Tên: {$p['name']} | Giá: {$price} | Loại: {$p['category']}\n";
        }

        $systemPrompt = "Bạn là CHUYÊN GIA TƯ VẤN MÁY TÍNH tại cửa hàng TechExpert.
        Nhiệm vụ của bạn là dựa trên yêu cầu của khách hàng để tìm ra NHỮNG sản phẩm phù hợp nhất từ danh sách bên dưới.

        DANH SÁCH SẢN PHẨM HIỆN CÓ:
        $productContext

        YÊU CẦU CỦA KHÁCH HÀNG: \"$userQuery\"

        QUY TẮC PHẢN HỒI:
        1. Phân tích nhu cầu khách hàng (Ví dụ: Chơi game, Đồ họa, Văn phòng, Ngân sách bao nhiêu).
        2. Đề xuất TỐI ĐA 4 sản phẩm phù hợp nhất.
        3. Định dạng câu trả lời bằng JSON NHƯ SAU (chỉ trả về JSON, không kèm văn bản khác):
        {
          \"analysis\": \"Câu phân tích ngắn gọn về nhu cầu khách hàng\",
          \"recommendations\": [
            {
              \"id\": \"ID của sản phẩm\",
              \"reason\": \"Lý do tại sao sản phẩm này phù hợp (ngắn gọn)\"
            }
          ]
        }";

        $rawResponse = $gemini->generateResponse($systemPrompt);
        
        // Extract JSON string from any wrapping conversational text
        if (preg_match('/\{.*\}/s', $rawResponse, $matches)) {
            $jsonStr = $matches[0];
        } else {
            $jsonStr = $rawResponse;
        }
        $jsonStr = preg_replace('/```json|```/', '', $jsonStr);
        $result = json_decode(trim($jsonStr), true);

        $recommendations = null;
        if (is_array($result)) {
            if (isset($result['recommendations'])) {
                $recommendations = $result['recommendations'];
            } elseif (isset($result['products'])) {
                $recommendations = $result['products'];
            }
        }

        if (!$result || !$recommendations || !is_array($recommendations)) {
            throw new Exception("AI không thể xử lý yêu cầu lúc này.");
        }

        // Enrich recommendations with full product data
        $enriched = [];
        foreach ($recommendations as $rec) {
            $productId = $rec['id'] ?? ($rec['product_id'] ?? null);
            if (!$productId) continue;

            $product = $this->productModel->getProductById($productId);
            if ($product) {
                $product['ai_reason'] = $rec['reason'] ?? ($rec['description'] ?? '');
                $product['formatted_price'] = number_format($product['price'], 0, ',', '.') . " VNĐ";
                $product['main_image'] = get_product_image($product['main_image']);
                $enriched[] = $product;
            }
        }

        return [
            'analysis' => $result['analysis'] ?? '',
            'products' => $enriched
        ];
    }
}
