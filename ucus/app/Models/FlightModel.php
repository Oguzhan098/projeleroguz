<?php
class FlightModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllFlights() {
        $sql = "
            SELECT 
                f.*,
                p.brand,
                p.model,
                a.name AS airport_name
            FROM flights f
            LEFT JOIN plane p ON f.plane_id = p.id
            LEFT JOIN airport a ON f.airport_id = a.id
            ORDER BY f.id DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFlight(array $data) {
        $sql = "
            INSERT INTO flights (
                first_name, last_name, age, gender,
                departure, arrival, departure_date, departure_time, arrival_date, arrival_time,
                plane_id, airport_id, passenger_count
            ) VALUES (
                :first_name, :last_name, :age, :gender,
                :departure, :arrival, :departure_date, :departure_time, :arrival_date, :arrival_time,
                :plane_id, :airport_id, :passenger_count
            )
        ";
        $stmt = $this->pdo->prepare($sql);
        $params = [
            ':first_name'      => $data['first_name'] !== '' ? $data['first_name'] : null,
            ':last_name'       => $data['last_name'] !== '' ? $data['last_name'] : null,
            ':age'             => $data['age'] !== null ? $data['age'] : null,
            ':gender'          => $data['gender'] !== '' ? $data['gender'] : null,
            ':departure'       => $data['departure'],
            ':arrival'         => $data['arrival'],
            ':departure_date'  => $data['departure_date'],
            ':departure_time'  => $data['departure_time'],
            ':arrival_date'    => $data['arrival_date'],
            ':arrival_time'    => $data['arrival_time'],
            ':plane_id'        => $data['plane_id'],
            ':airport_id'      => $data['airport_id'],
            ':passenger_count' => $data['passenger_count'] ?? 1,
        ];

        $ok = $stmt->execute($params);
        if (!$ok) {

            return false;
        }

        return $this->pdo->lastInsertId();
    }

    public function deleteFlight($id) {
        $stmt = $this->pdo->prepare("DELETE FROM flights WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }

    public function clearFlights() {
        $this->pdo->exec("TRUNCATE TABLE flights RESTART IDENTITY CASCADE");
        return true;
    }

    public function getPlanes() {
        $stmt = $this->pdo->query("SELECT * FROM plane ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAirports() {
        $stmt = $this->pdo->query("SELECT * FROM airport ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
