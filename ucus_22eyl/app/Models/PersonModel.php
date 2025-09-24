<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Entities/Person.php';

class PersonModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create(Person $person) {
        $stmt = $this->pdo->prepare("INSERT INTO person (first_name, last_name, gender, age) VALUES (?, ?, ?, ?) RETURNING id");
        $stmt->execute([$person->first_name, $person->last_name, $person->gender, $person->age]);
        return $stmt->fetchColumn();
    }
}
