<?php
/**
 * Security Helper - Input Sanitization & XSS Filtering
 */

/**
 * Filter XSS from a string
 *
 * @param string $str Input string
 * @param bool $isRichText If true, preserves safe HTML formatting tags but removes scripting/exploits
 * @return string Cleaned string
 */
function xss_clean_string($str, $isRichText = false) {
    if (empty($str) || !is_string($str)) {
        return $str;
    }

    if (!$isRichText) {
        // Strip all HTML tags entirely for regular inputs
        $str = strip_tags($str);
        // Convert special characters to HTML entities for output safety
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    // Rich Text HTML logic: preserve safe HTML tags, remove dangerous script tags and events
    
    // 1. Remove dangerous elements (script, iframe, style, object, embed, applet, meta, link, frameset, frame)
    $dangerousElements = [
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
        '/<object\b[^>]*>(.*?)<\/object>/is',
        '/<embed\b[^>]*>(.*?)<\/embed>/is',
        '/<applet\b[^>]*>(.*?)<\/applet>/is',
        '/<meta\b[^>]*>/is',
        '/<link\b[^>]*>/is'
    ];
    $str = preg_replace($dangerousElements, '', $str);

    // 2. Remove inline Javascript event handlers (e.g., onclick, onload, onerror, etc.)
    // Matches attributes like onclick="...", onmouseover='...', or onerror=...
    $str = preg_replace('/(on[a-zA-Z]+)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]*)/is', '', $str);

    // 3. Remove javascript: URIs (e.g. <a href="javascript:... ">)
    $str = preg_replace('/href\s*=\s*("|\')?\s*javascript\s*:[^"\' >]*/is', 'href="#"', $str);

    return $str;
}

/**
 * Recursively sanitize an input array or string
 *
 * @param mixed $data Data to sanitize (array or string)
 * @param string|int $key Current array key
 * @return mixed Sanitized data
 */
function sanitize_data($data, $key = '') {
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = sanitize_data($v, $k);
        }
        return $data;
    }

    // Skip sanitization for password fields to preserve exact password characters
    if (strpos(strtolower((string)$key), 'password') !== false) {
        return $data;
    }

    // Define keys allowed to have Rich Text HTML
    $richTextKeys = ['description', 'specs', 'short_description', 'value', 'detail', 'content'];
    
    $isRichText = in_array(strtolower((string)$key), $richTextKeys);
    
    return xss_clean_string($data, $isRichText);
}

/**
 * Initialize global input sanitization
 */
function sanitize_global_inputs() {
    if (!empty($_GET)) {
        $_GET = sanitize_data($_GET);
    }
    if (!empty($_POST)) {
        $_POST = sanitize_data($_POST);
    }
    if (!empty($_REQUEST)) {
        $_REQUEST = sanitize_data($_REQUEST);
    }
}

/**
 * Chuẩn hóa đường dẫn ảnh sản phẩm hiển thị trên giao diện
 *
 * @param string $image Tên file hoặc đường dẫn ảnh
 * @return string Đường dẫn URL đầy đủ và hợp lệ
 */
function get_product_image($image) {
    if (empty($image)) {
        return 'https://placehold.co/400x400?text=No+Image';
    }
    
    // Nếu là URL tuyệt đối bắt đầu bằng http
    if (strpos($image, 'http') === 0) {
        // Sửa lỗi thiếu thư mục /public/ trong đường dẫn local lưu trên database
        if (strpos($image, 'localhost') !== false || strpos($image, '127.0.0.1') !== false) {
            if (strpos($image, '/public/') === false) {
                $image = str_replace('/img/', '/public/img/', $image);
            }
        }
        return $image;
    }
    
    // Nếu đường dẫn bắt đầu bằng /public/ hoặc public/
    if (strpos($image, '/public/') === 0) {
        return URLROOT . $image;
    }
    if (strpos($image, 'public/') === 0) {
        return URLROOT . '/' . $image;
    }
    
    // Nếu đường dẫn bắt đầu bằng /img/ hoặc img/
    if (strpos($image, '/img/') === 0) {
        return URLROOT . '/public' . $image;
    }
    if (strpos($image, 'img/') === 0) {
        return URLROOT . '/public/' . $image;
    }
    
    // Nếu là relative path chỉ chứa tên file ảnh (ví dụ: 1778652438_a.jpg)
    return URLROOT . '/public/img/products/' . $image;
}

