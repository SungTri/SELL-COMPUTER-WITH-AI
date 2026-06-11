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
