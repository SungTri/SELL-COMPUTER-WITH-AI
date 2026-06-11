<?php

class NewsletterModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Check if email is already subscribed
     *
     * @param string $email
     * @return bool
     */
    public function isSubscribed($email) {
        $this->db->query("SELECT id FROM newsletter_subscribers WHERE email = :email");
        $this->db->bind(':email', $email);
        $result = $this->db->single();
        return !empty($result);
    }

    /**
     * Add new subscriber email
     *
     * @param string $email
     * @return bool
     */
    public function addSubscriber($email) {
        $this->db->query("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    /**
     * Get all newsletter subscribers
     *
     * @return array
     */
    public function getAllSubscribers() {
        $this->db->query("SELECT email FROM newsletter_subscribers ORDER BY id ASC");
        return $this->db->resultSet();
    }
}
