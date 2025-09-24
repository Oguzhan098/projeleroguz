<?php
class PersonService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function all(): array {
        return $this->pdo->query("SELECT * FROM person ORDER BY id")->fetchAll();
    }

    public function create(array $d): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO person (first_name, last_name, gender, age) VALUES (:f,:l,:g,:a)");
        $stmt->execute([':f'=>$d['first_name'], ':l'=>$d['last_name'], ':g'=>$d['gender'], ':a'=>(int)$d['age']]);
    }
}
