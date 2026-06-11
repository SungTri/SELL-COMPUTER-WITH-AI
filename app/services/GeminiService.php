<?php

class GeminiService {
    private $apiKey;
    private $baseUrl = "https://generativelanguage.googleapis.com/v1beta";

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generateResponse($systemInstruction, $history = [], $latestPrompt = "") {
        // Cố định mô hình nhanh nhất làm mặc định để giảm độ trễ
        $defaultModel = "models/gemini-2.0-flash";
        
        // Chuẩn bị payload chứa lịch sử hội thoại
        $contents = [];
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg['role'],
                'parts' => [['text' => $msg['parts'][0]['text']]]
            ];
        }
        
        // Thêm câu hỏi mới nhất của người dùng
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $latestPrompt]]
        ];

        $payload = [
            "systemInstruction" => [
                "parts" => [["text" => $systemInstruction]]
            ],
            "contents" => $contents
        ];
        
        try {
            return $this->callGemini($defaultModel, $payload);
        } catch (Exception $e) {
            // Nếu lỗi mới bắt đầu đi dò tìm các mô hình khác (Fallback)
            return $this->fallbackToOtherModels($payload);
        }
    }

    private function callGemini($modelPath, $payload) {
        $url = "{$this->baseUrl}/{$modelPath}:generateContent?key=" . $this->apiKey;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }
            return "AI không trả về nội dung hoặc bị chặn nội dung.";
        }
        $errorMsg = "API Error: Code $httpCode";
        if ($response) {
            $errData = json_decode($response, true);
            $errorMsg .= " - " . ($errData['error']['message'] ?? 'Unknown error');
        }
        throw new Exception($errorMsg);
    }

    private function fallbackToOtherModels($payload) {
        $listUrl = "{$this->baseUrl}/models?key=" . $this->apiKey;
        $ch = curl_init($listUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $listResponse = curl_exec($ch);
        $listData = json_decode($listResponse, true);
        curl_close($ch);

        if (isset($listData['models'])) {
            foreach ($listData['models'] as $m) {
                $modelName = $m['name'];
                if ($modelName === "models/gemini-1.5-flash") continue; 
                
                if (in_array('generateContent', $m['supportedGenerationMethods'])) {
                    try {
                        return $this->callGemini($modelName, $payload);
                    } catch (Exception $e) { continue; }
                }
            }
        }
        throw new Exception("Tất cả các mô hình AI đều không phản hồi.");
    }
}
