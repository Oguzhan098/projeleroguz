<?php
class AirportModel {
    private PDO $db;
    public function __construct() {
        $cfg = require __DIR__ . '/../config/database.php';
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $cfg['driver'], $cfg['host'], $cfg['port'], $cfg['database'], $cfg['charset']);
        $this->db = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options'] ?? []);
    }
    public function all(): array {
        $st = $this->db->query("SELECT * FROM airports ORDER BY country, city, name");
        return $st->fetchAll();
    }
    public function create(array $data): int {
        $sql = "INSERT INTO airports (iata_code, icao_code, name, city, country, timezone, created_at)
                VALUES (:iata, :icao, :name, :city, :country, :tz, NOW())";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':iata'=>$data['iata_code'] ?? null,
            ':icao'=>$data['icao_code'] ?? null,
            ':name'=>$data['name'] ?? null,
            ':city'=>$data['city'] ?? null,
            ':country'=>$data['country'] ?? null,
            ':tz'=>$data['timezone'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }
}
