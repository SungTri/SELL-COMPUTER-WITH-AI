<?php

class Controller {
    public function model($model) {
        require_once MODELS . '/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        // Automatically inject categories if not already present, for the header
        if (!isset($data['categories'])) {
            require_once MODELS . '/CategoryModel.php';
            $categoryModel = new CategoryModel();
            $data['categories'] = $categoryModel->getCategoriesWithBrands();
        }

        // Global System Settings
        require_once MODELS . '/SystemModel.php';
        $systemModel = new SystemModel();
        $data['app_settings'] = $systemModel->getSettings();

        if (file_exists(VIEWS . '/' . $view . '.php')) {
            require_once VIEWS . '/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }
}
