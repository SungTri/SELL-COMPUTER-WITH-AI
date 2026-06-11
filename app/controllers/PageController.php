<?php

class PageController extends Controller {
    
    public function about() {
        $data = [
            'title' => 'Về chúng tôi - TechExpert'
        ];
        $this->view('pages/about', $data);
    }

    public function help() {
        $data = [
            'title' => 'Trung tâm trợ giúp - TechExpert'
        ];
        $this->view('pages/help', $data);
    }

    public function shipping() {
        $data = [
            'title' => 'Chính sách vận chuyển - TechExpert'
        ];
        $this->view('pages/shipping', $data);
    }

    public function warranty() {
        $data = [
            'title' => 'Bảo hành & Đổi trả - TechExpert'
        ];
        $this->view('pages/warranty', $data);
    }

    public function contact() {
        $data = [
            'title' => 'Liên hệ - TechExpert'
        ];
        $this->view('pages/contact', $data);
    }
}
