<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Entities/Flight.php';

class FlightService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getFlightsByAirportAndPlane($airport_id, $plane_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM flight WHERE airport_id = ? OR plane_id = ?");
        $stmt->execute([$airport_id, $plane_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(Flight $flight, $person_id) {
        $stmt = $this->pdo->prepare("INSERT INTO flight (from_city, to_city, passenger_count, start_time, end_time, plane_id, airport_id) VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id");
        $stmt->execute([
            $flight->from,
            $flight->to,
            $flight->passenger_count,
            $flight->start_time,
            $flight->end_time,
            $flight->plane_id,
            $flight->airport_id
        ]);
        $flight_id = $stmt->fetchColumn();

        $stmt2 = $this->pdo->prepare("INSERT INTO flight_person (flight_id, person_id) VALUES (?, ?)");
        $stmt2->execute([$flight_id, $person_id]);
    }
}
