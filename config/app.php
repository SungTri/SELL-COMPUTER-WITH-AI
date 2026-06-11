<?php
/**
 * Application Configuration
 */

// URL Root
define('URLROOT', $_ENV['URLROOT'] ?? 'http://localhost/CUAHANGMAYTINH/computer-shop');

// Site Name
define('SITENAME', $_ENV['SITENAME'] ?? 'TechExpert - Computer Shop');

// App Root
define('APPROOT', dirname(dirname(__FILE__)) . '/app');
define('ROOT', dirname(dirname(__FILE__)));

// Directory Constants
define('VIEWS', APPROOT . '/views');
define('MODELS', APPROOT . '/models');
define('CONTROLLERS', APPROOT . '/controllers');
define('CORE', APPROOT . '/core');
define('HELPERS', APPROOT . '/helpers');
define('SERVICES', APPROOT . '/services');

// AI Configuration
define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY'] ?? '');

// Casso Configuration
define('CASSO_WEBHOOK_TOKEN', $_ENV['CASSO_WEBHOOK_TOKEN'] ?? '');

// Brevo Email API Configuration (Đăng ký tài khoản miễn phí tại brevo.com để lấy API Key)
define('BREVO_API_KEY', $_ENV['BREVO_API_KEY'] ?? '');
define('SENDER_EMAIL', $_ENV['SENDER_EMAIL'] ?? '');
define('SENDER_NAME', $_ENV['SENDER_NAME'] ?? 'TechExpert Support');
