<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;
use RuntimeException;

final class FlightPerson extends Model
{
    /** @return array{0:string,1:string} */
    private function flightInterval(int $flightId): array {
        $st = $this->pdo->prepare("SELECT departure_ts, arrival_ts FROM public.flights WHERE id=:id");
        $st->execute([':id'=>$flightId]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row) throw new RuntimeException('Flight bulunamadı (id=' . $flightId . ')');
        return [$row['departure_ts'], $row['arrival_ts']];
    }

    private function personExists(int $personId): bool {
        $st = $this->pdo->prepare("SELECT 1 FROM public.person WHERE id=:id LIMIT 1");
        $st->execute([':id'=>$personId]);
        return (bool)$st->fetchColumn();
    }

    private function flightExists(int $flightId): bool {
        $st = $this->pdo->prepare("SELECT 1 FROM public.flights WHERE id=:id LIMIT 1");
        $st->execute([':id'=>$flightId]);
        return (bool)$st->fetchColumn();
    }

    private function existsMapping(int $flightId, int $personId): bool {
        $st = $this->pdo->prepare("SELECT 1 FROM public.flight_person WHERE flight_id=:f AND person_id=:p LIMIT 1");
        $st->execute([':f'=>$flightId, ':p'=>$personId]);
        return (bool)$st->fetchColumn();
    }

    private function personHasOverlap(int $personId, int $newFlightId): bool {
        [$nd, $na] = $this->flightInterval($newFlightId);
        $st = $this->pdo->prepare("
            SELECT 1
            FROM public.flight_person fp
            JOIN public.flights f ON f.id = fp.flight_id
            WHERE fp.person_id = :pid
              AND (:nd < f.arrival_ts AND f.departure_ts < :na)
            LIMIT 1
        ");
        $st->execute([':pid'=>$personId, ':nd'=>$nd, ':na'=>$na]);
        return (bool)$st->fetchColumn();
    }

    public function attach(int $flightId, int $personId): void {
        if (!$this->flightExists($flightId)) throw new RuntimeException('Flight bulunamadı (id=' . $flightId . ')');
        if (!$this->personExists($personId)) throw new RuntimeException('Kişi bulunamadı (id=' . $personId . ')');
        if ($this->existsMapping($flightId, $personId)) throw new RuntimeException('Bu kişi zaten bu uçuşa ekli.');
        if ($this->personHasOverlap($personId, $flightId)) throw new RuntimeException('Aynı kişi aynı zaman aralığında birden fazla uçuş yapamaz.');

        $st = $this->pdo->prepare("INSERT INTO public.flight_person (flight_id, person_id) VALUES (:f,:p)");
        $st->execute([':f'=>$flightId, ':p'=>$personId]);
    }

    public function detach(int $flightId, int $personId): void {
        $st = $this->pdo->prepare("DELETE FROM public.flight_person WHERE flight_id=:f AND person_id=:p");
        $st->execute([':f'=>$flightId, ':p'=>$personId]);
    }

    /** @return array<int,array<string,mixed>> */
    public function listByFlight(int $flightId): array {
        $st = $this->pdo->prepare("
            SELECT fp.person_id, pe.first_name, pe.last_name, pe.gender, pe.age
            FROM public.flight_person fp
            JOIN public.person pe ON pe.id = fp.person_id
            WHERE fp.flight_id = :f
            ORDER BY pe.last_name, pe.first_name
        ");
        $st->execute([':f'=>$flightId]);
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
