<?php

class ChatbotModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllData() {
        $this->db->query("SELECT * FROM chatbot_data ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getDataById($id) {
        $this->db->query("SELECT * FROM chatbot_data WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addData($data) {
        $this->db->query("INSERT INTO chatbot_data (question, answer, keywords) VALUES (:question, :answer, :keywords)");
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':keywords', $data['keywords']);
        return $this->db->execute();
    }

    public function updateData($id, $data) {
        $this->db->query("UPDATE chatbot_data SET question = :question, answer = :answer, keywords = :keywords WHERE id = :id");
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':keywords', $data['keywords']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteData($id) {
        $this->db->query("DELETE FROM chatbot_data WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getChatbotDataPaginated($search, $limit, $offset) {
        if (!empty($search)) {
            $isNumeric = is_numeric($search);
            $query = "SELECT * FROM chatbot_data 
                      WHERE question LIKE :search 
                         OR answer LIKE :search 
                         OR keywords LIKE :search";
            if ($isNumeric) {
                $query .= " OR id = :id_exact";
            }
            $query .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
            
            $this->db->query($query);
            $this->db->bind(':search', '%' . $search . '%');
            if ($isNumeric) {
                $this->db->bind(':id_exact', intval($search), PDO::PARAM_INT);
            }
        } else {
            $this->db->query("SELECT * FROM chatbot_data ORDER BY id DESC LIMIT :limit OFFSET :offset");
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalChatbotDataCount($search) {
        if (!empty($search)) {
            $isNumeric = is_numeric($search);
            $query = "SELECT COUNT(*) as total FROM chatbot_data 
                      WHERE question LIKE :search 
                         OR answer LIKE :search 
                         OR keywords LIKE :search";
            if ($isNumeric) {
                $query .= " OR id = :id_exact";
            }
            $this->db->query($query);
            $this->db->bind(':search', '%' . $search . '%');
            if ($isNumeric) {
                $this->db->bind(':id_exact', intval($search), PDO::PARAM_INT);
            }
        } else {
            $this->db->query("SELECT COUNT(*) as total FROM chatbot_data");
        }
        return $this->db->single()['total'];
    }

    public function getChatHistoryPaginated($search, $limit, $offset) {
        if (!empty($search)) {
            $this->db->query("SELECT * FROM chat_history 
                              WHERE question LIKE :search 
                                 OR answer LIKE :search 
                                 OR customer_id LIKE :search
                              ORDER BY chatted_at DESC LIMIT :limit OFFSET :offset");
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query("SELECT * FROM chat_history ORDER BY chatted_at DESC LIMIT :limit OFFSET :offset");
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getTotalChatHistoryCount($search) {
        if (!empty($search)) {
            $this->db->query("SELECT COUNT(*) as total FROM chat_history 
                              WHERE question LIKE :search 
                                 OR answer LIKE :search 
                                 OR customer_id LIKE :search");
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query("SELECT COUNT(*) as total FROM chat_history");
        }
        return $this->db->single()['total'];
    }

    public function getChatHistory($limit = 50) {
        $this->db->query("SELECT * FROM chat_history ORDER BY chatted_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}
