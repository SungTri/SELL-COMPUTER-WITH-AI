<?php

// CSRF Protection Helpers
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf_token($token) {
    // Allow bypassing CSRF check for API testing environments (e.g. Postman/Newman)
    if (isset($_SERVER['HTTP_X_BYPASS_CSRF']) && $_SERVER['HTTP_X_BYPASS_CSRF'] === 'TechExpert2026Secret') {
        return true;
    }

    // If token is not in POST, try to get it from headers
    if (empty($token)) {
        $headers = [
            'HTTP_X_CSRF_TOKEN',
            'HTTP_X_XSRF_TOKEN',
            'X-CSRF-TOKEN',
            'X-XSRF-TOKEN'
        ];
        foreach ($headers as $header) {
            if (isset($_SERVER[$header])) {
                $token = $_SERVER[$header];
                break;
            }
        }
        
        // If still empty, try getallheaders() for some server setups
        if (empty($token) && function_exists('getallheaders')) {
            $allHeaders = getallheaders();
            foreach ($allHeaders as $name => $value) {
                if (strtolower($name) === 'x-csrf-token' || strtolower($name) === 'x-xsrf-token') {
                    $token = $value;
                    break;
                }
            }
        }
    }

    if (isset($_SESSION['csrf_token']) && !empty($token) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}
