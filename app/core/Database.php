<?php

class Database {
    private $host = DB_HOST;
    private $port = DB_PORT;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            
            // Check if this is an API or AJAX request
            $is_api_or_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
                || (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
                || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            
            if ($is_api_or_ajax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Database connection failed: ' . $this->error
                ]);
            } else {
                echo '<div style="font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; max-width: 650px; margin: 50px auto; padding: 30px; background-color: #fce8e6; border-left: 5px solid #d93025; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
                echo '<h2 style="color: #c5221f; margin-top: 0; font-size: 20px;">Lỗi kết nối Cơ sở dữ liệu (Database Connection Error)</h2>';
                echo '<p style="color: #3c4043; line-height: 1.6; font-size: 15px;">Hệ thống không thể kết nối tới cơ sở dữ liệu. Vui lòng kiểm tra lại cấu hình kết nối hoặc trạng thái máy chủ cơ sở dữ liệu.</p>';
                echo '<div style="background-color: #ffffff; padding: 15px; border-radius: 4px; border: 1px solid #fad2cf; font-family: monospace; white-space: pre-wrap; word-break: break-all; margin: 15px 0; color: #b00020; font-size: 13px; line-height: 1.4;">' . htmlspecialchars($this->error) . '</div>';
                echo '<div style="color: #5f6368; font-size: 14px; line-height: 1.6; border-top: 1px solid #fad2cf; padding-top: 15px;">';
                echo '<b style="color: #3c4043;">💡 Hướng dẫn khắc phục:</b>';
                echo '<ul style="margin: 5px 0 0 20px; padding: 0;">';
                echo '<li><b>Trường hợp cơ sở dữ liệu trên Aiven (Render deploy):</b> Đăng nhập vào bảng điều khiển <a href="https://console.aiven.io/" target="_blank" style="color: #1a73e8; text-decoration: none;">Aiven Console</a>, kiểm tra xem MySQL Service có bị <b>PAUSED</b> (Tạm dừng) hoặc <b>DELETED</b> (Bị xóa do hết hạn tài khoản thử nghiệm) hay không. Nếu bị <b>PAUSED</b>, nhấn nút <b>Resume</b> để chạy lại.</li>';
                echo '<li><b>Trường hợp chạy local:</b> Đảm bảo bạn đã khởi động Apache và MySQL trong XAMPP Panel, và thông tin cấu hình trong file <code>.env</code> trùng khớp với thông tin DB của bạn.</li>';
                echo '<li><b>Kiểm tra Render Environment Variables:</b> Đối soát xem các biến môi trường <code>DB_HOST</code>, <code>DB_USER</code>, <code>DB_PASS</code>, <code>DB_PORT</code> trên Render Dashboard đã được cập nhật chính xác chưa.</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
            exit;
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        $results = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        if (function_exists('translate_db_results')) {
            $results = translate_db_results($results);
        }
        return $results;
    }

    public function single() {
        $this->execute();
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && function_exists('translate_db_results')) {
            $result = translate_db_results($result);
        }
        return $result;
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
