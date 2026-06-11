<?php
/**
 * Language Switcher Controller
 */
class LanguageController extends Controller {
    /**
     * Change the system active language
     * 
     * @param string $lang Target language code ('vi' or 'en')
     * @return void
     */
    public function change($lang = 'vi') {
        // Only allow supported languages
        if (in_array($lang, ['vi', 'en'])) {
            $_SESSION['lang'] = $lang;
        }

        // Redirect user back to the referer page, or default to URLROOT
        $referer = $_SERVER['HTTP_REFERER'] ?? URLROOT;
        
        // Prevent open redirect by checking if the referer is on our host
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        if (strpos($referer, $host) !== false) {
            header('Location: ' . $referer);
        } else {
            header('Location: ' . URLROOT);
        }
        exit();
    }
}
