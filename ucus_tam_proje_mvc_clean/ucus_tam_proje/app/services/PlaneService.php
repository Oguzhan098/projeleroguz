<?php
class PlaneService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function all(): array {
        return $this->pdo->query("SELECT * FROM plane ORDER BY id")->fetchAll();
    }

    public function create(array $d): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO plane (brand, model, capacity, year) VALUES (:b, :m, :c, :y)");
        $stmt->execute([':b'=>$d['brand']?:null, ':m'=>$d['model'],
            ':c'=>(int)$d['capacity'], ':y'=>$d['year']? (int)$d['year']: null]);
    }
}
