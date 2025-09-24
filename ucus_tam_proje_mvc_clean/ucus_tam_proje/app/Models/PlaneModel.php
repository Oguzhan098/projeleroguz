<?php
class PlaneModel {
    private PDO $db;
    public function __construct() {
        $cfg = require __DIR__ . '/../config/database.php';
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $cfg['driver'], $cfg['host'], $cfg['port'], $cfg['database'], $cfg['charset']);
        $this->db = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options'] ?? []);
    }
    public function all(): array {
        $st = $this->db->query("SELECT * FROM planes ORDER BY manufacturer, model, registration");
        return $st->fetchAll();
    }
    public function create(array $data): int {
        $sql = "INSERT INTO planes (registration, model, manufacturer, capacity, created_at)
                VALUES (:reg, :model, :manu, :cap, NOW())";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':reg'=>$data['registration'] ?? null,
            ':model'=>$data['model'] ?? null,
            ':manu'=>$data['manufacturer'] ?? null,
            ':cap'=>$data['capacity'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }
}
