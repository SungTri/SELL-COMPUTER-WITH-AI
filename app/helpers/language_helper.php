<?php
/**
 * Language Helper
 */

// Initialize default language in session if not set
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'vi'; // Default to Vietnamese
}

/**
 * Get the loaded language dictionary array
 * 
 * @return array
 */
function get_language_dictionary() {
    static $dictionary = null;
    if ($dictionary === null) {
        $lang = $_SESSION['lang'] ?? 'vi';
        
        // Sanitize selection to prevent path traversal
        if (!in_array($lang, ['vi', 'en'])) {
            $lang = 'vi';
        }
        
        $filePath = APPROOT . "/languages/{$lang}.php";
        if (file_exists($filePath)) {
            $dictionary = require $filePath;
        } else {
            $dictionary = [];
        }
    }
    return $dictionary;
}

/**
 * Translate helper function
 * 
 * @param string $key Translation key
 * @param string $default Fallback value if key is not found
 * @return string Translated string
 */
function __($key, $default = '') {
    $dictionary = get_language_dictionary();
    return $dictionary[$key] ?? ($default ?: $key);
}

/**
 * Dynamically translate database fields to English if the current session language is English.
 * 
 * @param array $data Database row or array of rows
 * @return array Translated data
 */
function translate_db_results($data) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $lang = $_SESSION['lang'] ?? 'vi';
    if ($lang !== 'en') {
        return $data;
    }
    
    // Core translation substitutions for IT & computer hardware terms
    static $replacements = [
        'Tản nhiệt' => 'Cooler',
        'TẢN NHIỆT' => 'COOLER',
        'tản nhiệt' => 'cooler',
        'Vỏ Case' => 'PC Case',
        'Vỏ case' => 'PC case',
        'vỏ case' => 'pc case',
        'Phụ kiện' => 'Accessories',
        'phụ kiện' => 'accessories',
        'PHỤ KIỆN' => 'ACCESSORIES',
        'Màn hình' => 'Monitor',
        'MÀN HÌNH' => 'MONITOR',
        'màn hình' => 'monitor',
        'Nguồn' => 'Power Supply',
        'NGUỒN' => 'POWER SUPPLY',
        'nguồn' => 'power supply',
        'Ổ cứng' => 'Storage',
        'Ổ Cứng' => 'Storage',
        'ổ cứng' => 'storage',
        'Bộ vi xử lý' => 'Processor (CPU)',
        'Bo mạch chủ' => 'Motherboard',
        'Card màn hình' => 'Graphics Card',
        'Trình trạng' => 'Condition',
        'Mới 100%, chính hãng' => 'New 100%, genuine',
        'Còn hàng' => 'In stock',
        'Hết hàng' => 'Out of stock',
        'Sản phẩm tương tự' => 'Similar Products',
        'Xem tất cả' => 'View all',
        'Chi tiết' => 'Details',
        'Đăng nhập ngay' => 'Login now',
        'Bàn phím' => 'Keyboard',
        'Chuột' => 'Mouse',
        'Tai nghe' => 'Headset',
        'Cáp chuyển' => 'Adapter Cable',
        'Đế tản nhiệt' => 'Laptop Cooler',
        'Ghế gaming' => 'Gaming Chair',
        'Bàn gaming' => 'Gaming Desk',
        'Cong' => 'Curved',
        'nhân' => 'cores',
        'luồng' => 'threads',
        'Đã thanh toán' => 'Paid',
        'Chờ thanh toán' => 'Pending payment',
        'Chính hãng' => 'Genuine',
    ];
    
    if (is_array($data)) {
        if (count($data) > 0 && isset($data[0]) && is_array($data[0])) {
            foreach ($data as &$row) {
                $row = translate_row($row, $replacements);
            }
            unset($row);
        } else {
            $data = translate_row($data, $replacements);
        }
    }
    
    return $data;
}

/**
 * Translate string fields in a single database row.
 */
function translate_row($row, $replacements) {
    $fields = [
        'name', 
        'short_description', 
        'detailed_description', 
        'specs', 
        'variant_name',
        'brand_name',
        'category_name',
        'title'
    ];
    
    foreach ($fields as $field) {
        if (isset($row[$field]) && is_string($row[$field]) && !empty($row[$field])) {
            foreach ($replacements as $vi => $en) {
                $row[$field] = str_replace($vi, $en, $row[$field]);
            }
        }
    }
    return $row;
}

