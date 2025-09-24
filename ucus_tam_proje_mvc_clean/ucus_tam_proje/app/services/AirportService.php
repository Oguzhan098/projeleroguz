<?php
class AirportService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function all(): array {
        return $this->pdo->query("SELECT * FROM airport ORDER BY id")->fetchAll();
    }

    public function create(array $d): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO airport (name, pist_sayisi, ucak_kapasitesi) VALUES (:n, :p, :u)");
        $stmt->execute(
            [':n'=>$d['name'],
                ':p'=>(int)$d['pist_sayisi'],
                ':u'=>(int)$d['ucak_kapasitesi']
            ]
        );
    }
}
