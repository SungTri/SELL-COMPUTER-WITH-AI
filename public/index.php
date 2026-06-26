<?php
session_start();
file_put_contents(__DIR__ . '/url_log.txt', date('Y-m-d H:i:s') . ' - URL: ' . ($_GET['url'] ?? 'NULL') . ' - URI: ' . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

// Load Composer Autoloader & Dotenv
require_once dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

// Load Config
require_once '../config/app.php';
require_once '../config/database.php';


// Autoload classes
spl_autoload_register(function($className) {
    $parts = explode('\\', $className);
    $className = end($parts);
    
    $paths = [
        CORE . '/' . $className . '.php',
        CONTROLLERS . '/' . $className . '.php',
        MODELS . '/' . $className . '.php',
        SERVICES . '/' . $className . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load Helpers
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/language_helper.php';
require_once '../app/helpers/security_helper.php';

// Global Input Sanitization (XSS and input security filtering)
sanitize_global_inputs();

// Global CSRF Protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exclude certain endpoints from CSRF protection
    $url = $_GET['url'] ?? '';
    $csrfExemptRoutes = [
        'casso/webhook',
        'admin/acceptSupportSession',
        'admin/closeSupportSession',
        'admin/sendLiveChatMessage',
        'chatbot/sendMessage',
        'chatbot/requestSupport',
    ];
    
    $isCsrfExempt = false;
    foreach ($csrfExemptRoutes as $route) {
        if (strpos($url, $route) !== false) {
            $isCsrfExempt = true;
            break;
        }
    }
    
    if (!$isCsrfExempt) {
        $token = $_POST['csrf_token'] ?? '';
        if (!verify_csrf_token($token)) {
            http_response_code(403);
            // Return JSON if AJAX request, else plain text
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            $acceptsJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
            if ($isAjax || $acceptsJson) {
                header('Content-Type: application/json; charset=utf-8');
                die(json_encode(['status' => 'error', 'message' => 'CSRF token invalid. Please refresh the page.']));
            }
            die('CSRF token validation failed.');
        }
    }
}

// Initialize App
$app = new App();
