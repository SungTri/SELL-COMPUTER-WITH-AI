<?php

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Controller
        if ($url && file_exists(CONTROLLERS . '/' . ucwords($url[0]) . 'Controller.php')) {
            $this->controller = ucwords($url[0]) . 'Controller';
            unset($url[0]);
        } elseif ($url) {
            // Invalid Controller
            $this->show404();
            return;
        }

        require_once CONTROLLERS . '/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Invalid Method
                $this->show404();
                return;
            }
        }

        // Params
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function show404() {
        http_response_code(404);
        require_once CONTROLLERS . '/HomeController.php';
        $home = new HomeController();
        // We can just require the view or use a dedicated method
        // For simplicity and consistency with current layout, we require the 404 view
        // But 404 needs APPROOT etc which are defined.
        require_once VIEWS . '/errors/404.php';
        exit();
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return null;
    }
}
