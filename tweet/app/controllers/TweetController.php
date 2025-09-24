<?php
class TweetController {
    private $db;

    public function __construct($db) {
        $this->db = $db->pdo;
    }

    public function create($user_id, $content) {
        if (strlen($content) > 180) return false;
        $stmt = $this->db->prepare("INSERT INTO tweets (user_id, content) VALUES (:user_id, :content)");
        return $stmt->execute(['user_id' => $user_id, 'content' => $content]);
    }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT tweets.*, users.username
            FROM tweets
            JOIN users ON tweets.user_id = users.id
            ORDER BY tweets.created_at DESC
        ");
        return $stmt->fetchAll();
    }
}
