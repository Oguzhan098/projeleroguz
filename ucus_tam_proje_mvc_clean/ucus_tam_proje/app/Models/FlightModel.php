<?php
class FlightModel {
    private PDO $db;
    public function __construct() {
        $cfg = require __DIR__ . '/../config/database.php';
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $cfg['driver'], $cfg['host'], $cfg['port'], $cfg['database'], $cfg['charset']);
        $this->db = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options'] ?? []);
    }
    public function allWithJoins(): array {
        $sql = "SELECT f.*, p.registration, p.model, a1.name AS origin_name, a2.name AS destination_name
                FROM flights f
                JOIN planes p ON p.id=f.plane_id
                JOIN airports a1 ON a1.id=f.origin_airport_id
                JOIN airports a2 ON a2.id=f.destination_airport_id
                ORDER BY f.departure_time_utc DESC";
        return $this->db->query($sql)->fetchAll();
    }
    public function create(array $d): int {
        $sql = "INSERT INTO flights (flight_no, plane_id, origin_airport_id, destination_airport_id, departure_time_utc, arrival_time_utc, status, gate, created_at)
                VALUES (:no, :plane, :org, :dst, :dep, :arr, :status, :gate, NOW())";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':no'=>$d['flight_no'], ':plane'=>$d['plane_id'],
            ':org'=>$d['origin_airport_id'], ':dst'=>$d['destination_airport_id'],
            ':dep'=>$d['departure_time_utc'], ':arr'=>$d['arrival_time_utc'],
            ':status'=>$d['status'] ?? 'scheduled', ':gate'=>$d['gate'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }
    public function find(int $id): ?array {
        $st = $this->db->prepare("SELECT * FROM flights WHERE id=:id");
        $st->execute([':id'=>$id]);
        $row = $st->fetch();
        return $row ?: null;
    }
    public function passengers(int $flightId): array {
        $sql = "SELECT fp.*, pe.first_name, pe.last_name, pe.email
                FROM flight_passengers fp
                JOIN people pe ON pe.id = fp.person_id
                WHERE fp.flight_id = :fid
                ORDER BY pe.last_name, pe.first_name";
        $st = $this->db->prepare($sql);
        $st->execute([':fid'=>$flightId]);
        return $st->fetchAll();
    }
    public function addPassenger(int $flightId, int $personId, ?string $seat, ?string $ticket): int {
        // Capacity check
        $capSql = "SELECT p.capacity, COUNT(fp.id) pax
                   FROM flights f JOIN planes p ON p.id=f.plane_id
                   LEFT JOIN flight_passengers fp ON fp.flight_id=f.id
                   WHERE f.id=:fid GROUP BY p.capacity";
        $st = $this->db->prepare($capSql);
        $st->execute([':fid'=>$flightId]);
        $row = $st->fetch();
        if ($row && (int)$row['pax'] >= (int)$row['capacity']) {
            throw new RuntimeException('Flight capacity is full.');
        }
        $sql = "INSERT INTO flight_passengers (flight_id, person_id, seat_no, ticket_no, created_at)
                VALUES (:fid, :pid, :seat, :ticket, NOW())";
        $st = $this->db->prepare($sql);
        $st->execute([':fid'=>$flightId, ':pid'=>$personId, ':seat'=>$seat, ':ticket'=>$ticket]);
        return (int)$this->db->lastInsertId();
    }
}
