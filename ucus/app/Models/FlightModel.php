<?php
class FlightModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllFlights() {
        $stmt = $this->pdo->query("
            SELECT 
                f.*,
                p.brand,
                p.model,
                a.name AS airport_name,
                f.first_name,
                f.last_name,
                f.age,
                f.gender
            FROM flights f
            JOIN plane p ON f.plane_id = p.id
            JOIN airport a ON f.airport_id = a.id
            LEFT JOIN flight_person fp ON fp.flight_id = f.id
            LEFT JOIN person per ON per.id = fp.person_id
            ORDER BY f.id desc 
        ");

        $flightsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $flights = [];

        foreach ($flightsRaw as $row) {
            $id = $row['id'];

            if (!isset($flights[$id])) {
                $flights[$id] = $row;
                $flights[$id]['passengers'] = [];
            }

            if ($row['first_name']) {
                $flights[$id]['passengers'][] = [
                    'first_name' => $row['first_name'],
                    'last_name'  => $row['last_name'],
                    'age'        => $row['age'],
                    'gender'     => $row['gender']
                ];
            }
        }

        return array_values($flights);
    }

    public function addFlight($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO flights (
                                 first_name, 
                                 last_name, 
                                 age, 
                                 gender,
                         
                departure, 
                arrival, 
                departure_date, 
                departure_time, 
                arrival_date, 
                arrival_time, 
                plane_id, 
                airport_id, 
                passenger_count
            ) VALUES (
               :first_name, :last_name, :age, :gender, :departure, :arrival, :departure_date, :departure_time, :arrival_date, :arrival_time, :plane_id, :airport_id, :passenger_count
            )
        ");

        return $stmt->execute([
            ':first_name'      => $data['first_name'],
            ':last_name'      => $data['last_name'],
            ':age'      => $data['age'],
            ':gender'      => $data['gender'],
            ':departure'      => $data['departure'],
            ':arrival'        => $data['arrival'],
            ':departure_date' => $data['departure_date'],
            ':departure_time' => $data['departure_time'],
            ':arrival_date'   => $data['arrival_date'],
            ':arrival_time'   => $data['arrival_time'],
            ':plane_id'       => $data['plane_id'],
            ':airport_id'     => $data['airport_id'],
            ':passenger_count'=> $data['passenger_count'] ?? 1
        ]);
    }

    public function getPlanes() {
        $stmt = $this->pdo->query("SELECT * FROM plane");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAirports() {
        $stmt = $this->pdo->query("SELECT * FROM airport");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
