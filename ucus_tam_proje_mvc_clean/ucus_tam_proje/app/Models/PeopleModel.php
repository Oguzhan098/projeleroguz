<?php
class PeopleModel {
    private PDO $db;
    public function __construct() {
        $cfg = require __DIR__ . '/../config/database.php';
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $cfg['driver'], $cfg['host'], $cfg['port'], $cfg['database'], $cfg['charset']);
        $this->db = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options'] ?? []);
    }
    public function all(): array {
        $st = $this->db->query("SELECT * FROM people ORDER BY last_name, first_name");
        return $st->fetchAll();
    }
    public function create(array $data): int {
        $sql = "INSERT INTO people (first_name, last_name, email, phone, national_id, birth_date, created_at)
                VALUES (:fn, :ln, :email, :phone, :nid, :bdate, NOW())";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':fn'=>$data['first_name'] ?? null,
            ':ln'=>$data['last_name'] ?? null,
            ':email'=>$data['email'] ?? null,
            ':phone'=>$data['phone'] ?? null,
            ':nid'=>$data['national_id'] ?? null,
            ':bdate'=>$data['birth_date'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }
}
