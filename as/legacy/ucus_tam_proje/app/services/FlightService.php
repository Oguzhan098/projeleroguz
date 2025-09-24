<?php
class FlightService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function all(): array {
        $sql = "SELECT f.id,
                       dep.name AS dep_airport, arr.name AS arr_airport,
                       f.departure_ts, f.arrival_ts,
                       p.model AS plane_model,
                       (SELECT json_agg(json_build_object('id', pe.id, 'first_name', 
                       pe.first_name, 'last_name', pe.last_name))
                        FROM flight_person fp
                        JOIN person pe ON pe.id = fp.person_id
                        WHERE fp.flight_id = f.id) AS passengers
                FROM flights f
                JOIN airport dep ON dep.id = f.departure_airport_id
                JOIN airport arr ON arr.id = f.arrival_airport_id
                JOIN plane p ON p.id = f.plane_id
                ORDER BY f.departure_ts DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(array $data): void {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO flights (
                     departure_airport_id, arrival_airport_id, plane_id, departure_ts, arrival_ts)
                                         VALUES (:dep, :arr, :plane, :dts, :ats)
                                         RETURNING id");
            $stmt->execute([
                ':dep' => (int)$data['departure_airport_id'],
                ':arr' => (int)$data['arrival_airport_id'],
                ':plane' => (int)$data['plane_id'],
                ':dts' => $data['departure_ts'],
                ':ats' => $data['arrival_ts'],
            ]);
            $flightId = (int)$stmt->fetchColumn();

            if (!empty($data['passenger_ids'])) {
                $ins = $this->pdo->prepare(
                    "INSERT INTO flight_person (flight_id, person_id) VALUES (:f, :p)");
                foreach ((array)$data['passenger_ids'] as $pid) {
                    $ins->execute([':f' => $flightId, ':p' => (int)$pid]);
                }
            }
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            $_SESSION['flash'] = 'Hata: ' . $e->getMessage();
            throw $e;
        }
    }

    public function delete(int $id): void {
        $this->pdo->prepare("DELETE FROM flight_person WHERE flight_id = :id")->execute([':id' => $id]);
        $this->pdo->prepare("DELETE FROM flights WHERE id = :id")->execute([':id' => $id]);
    }
}
