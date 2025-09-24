<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;
use RuntimeException;

final class Flight extends Model
{

    public function all(): array {
        $sql = "SELECT 
                  f.*,
                  COALESCE(da.name,'?') AS dep_name,
                  COALESCE(aa.name,'?') AS arr_name,
                  COALESCE(p.model,'?') AS plane_model
                FROM public.flights f
                LEFT JOIN public.airport da ON da.id = f.departure_airport_id
                LEFT JOIN public.airport aa ON aa.id = f.arrival_airport_id
                LEFT JOIN public.plane   p  ON p.id = f.plane_id
                ORDER BY f.id DESC";
        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }

    public function find(int $id): ?array {
        $st = $this->pdo->prepare("SELECT * FROM public.flights WHERE id=:id");
        $st->execute([':id'=>$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function validateTimes(string $depTs, string $arrTs): void {
        if (strtotime($arrTs) <= strtotime($depTs)) {
            throw new RuntimeException('arrival_ts > departure_ts olmalı.');
        }
    }

    private function existsPlaneOverlap(int $planeId, string $depTs, string $arrTs, ?int $excludeId=null): bool {
        $sql = "SELECT 1 FROM public.flights f
                WHERE f.plane_id=:pid
                  AND (:ex IS NULL OR f.id<>:ex)
                  AND (:depTs < f.arrival_ts AND f.departure_ts < :arrTs)
                LIMIT 1";
        $st = $this->pdo->prepare($sql);
        $st->execute([':pid'=>$planeId, ':ex'=>$excludeId, ':depTs'=>$depTs, ':arrTs'=>$arrTs]);
        return (bool)$st->fetchColumn();
    }

    private function concurrentDepartures(int $airportId, string $depTs, string $arrTs, ?int $excludeId=null): int {
        $st = $this->pdo->prepare("SELECT COUNT(*) FROM public.flights f
            WHERE f.departure_airport_id=:a AND (:ex IS NULL OR f.id<>:ex)
              AND (:depTs < f.arrival_ts AND f.departure_ts < :arrTs)");
        $st->execute([':a'=>$airportId, ':ex'=>$excludeId, ':depTs'=>$depTs, ':arrTs'=>$arrTs]);
        return (int)$st->fetchColumn();
    }

    private function concurrentArrivals(int $airportId, string $depTs, string $arrTs, ?int $excludeId=null): int {
        $st = $this->pdo->prepare("SELECT COUNT(*) FROM public.flights f
            WHERE f.arrival_airport_id=:a AND (:ex IS NULL OR f.id<>:ex)
              AND (:depTs < f.arrival_ts AND f.departure_ts < :arrTs)");
        $st->execute([':a'=>$airportId, ':ex'=>$excludeId, ':depTs'=>$depTs, ':arrTs'=>$arrTs]);
        return (int)$st->fetchColumn();
    }

    private function concurrentOpsTouching(int $airportId, string $depTs, string $arrTs, ?int $excludeId=null): int {
        $st = $this->pdo->prepare("SELECT COUNT(*) FROM public.flights f
            WHERE (:ex IS NULL OR f.id<>:ex)
              AND (f.departure_airport_id=:a OR f.arrival_airport_id=:a)
              AND (:depTs < f.arrival_ts AND f.departure_ts < :arrTs)");
        $st->execute([':a'=>$airportId, ':ex'=>$excludeId, ':depTs'=>$depTs, ':arrTs'=>$arrTs]);
        return (int)$st->fetchColumn();
    }

    /** @return array<string,mixed> */
    private function getAirport(int $id): array {
        $st = $this->pdo->prepare("SELECT * FROM public.airport WHERE id=:id");
        $st->execute([':id'=>$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row) throw new RuntimeException('Airport bulunamadı');
        return $row;
    }

    /** @param array<string,mixed> $data */
    public function create(array $data): int {
        $this->validateTimes($data['departure_ts'], $data['arrival_ts']);
        $depA = $this->getAirport((int)$data['departure_airport_id']);
        $arrA = $this->getAirport((int)$data['arrival_airport_id']);

        if ($this->existsPlaneOverlap((int)$data['plane_id'], $data['departure_ts'], $data['arrival_ts'], null)) {
            throw new RuntimeException('Aynı uçak aynı zaman aralığında birden fazla uçuş yapamaz.');
        }

        $depOps = $this->concurrentDepartures((int)$data['departure_airport_id'], $data['departure_ts'], $data['arrival_ts'], null);
        $arrOps = $this->concurrentArrivals((int)$data['arrival_airport_id'], $data['departure_ts'], $data['arrival_ts'], null);
        if ($depOps >= (int)$depA['pist_sayisi']) throw new RuntimeException('Kalkış havalimanında eşzamanlı operasyon sayısı pist sayısını aşıyor.');
        if ($arrOps >= (int)$arrA['pist_sayisi']) throw new RuntimeException('Varış havalimanında eşzamanlı operasyon sayısı pist sayısını aşıyor.');

        $depConc = $this->concurrentOpsTouching((int)$data['departure_airport_id'], $data['departure_ts'], $data['arrival_ts'], null);
        $arrConc = $this->concurrentOpsTouching((int)$data['arrival_airport_id'],   $data['departure_ts'], $data['arrival_ts'], null);
        if ($depConc >= (int)$depA['ucak_kapasitesi']) throw new RuntimeException('Kalkış havalimanında eşzamanlı uçak sayısı kapasiteyi aşıyor.');
        if ($arrConc >= (int)$arrA['ucak_kapasitesi']) throw new RuntimeException('Varış havalimanında eşzamanlı uçak sayısı kapasiteyi aşıyor.');

        $st = $this->pdo->prepare("INSERT INTO public.flights (departure_airport_id, arrival_airport_id, plane_id, departure_ts, arrival_ts)
                                   VALUES (:da,:aa,:p,:d,:a) RETURNING id");
        $st->execute([
            ':da'=>$data['departure_airport_id'],
            ':aa'=>$data['arrival_airport_id'],
            ':p'=>$data['plane_id'],
            ':d'=>$data['departure_ts'],
            ':a'=>$data['arrival_ts'],
        ]);
        return (int)$st->fetchColumn();
    }

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): void {
        $this->validateTimes($data['departure_ts'], $data['arrival_ts']);
        $depA = $this->getAirport((int)$data['departure_airport_id']);
        $arrA = $this->getAirport((int)$data['arrival_airport_id']);

        if ($this->existsPlaneOverlap((int)$data['plane_id'], $data['departure_ts'], $data['arrival_ts'], $id)) {
            throw new RuntimeException('Aynı uçak aynı zaman aralığında birden fazla uçuş yapamaz.');
        }

        $depOps = $this->concurrentDepartures((int)$data['departure_airport_id'], $data['departure_ts'], $data['arrival_ts'], $id);
        $arrOps = $this->concurrentArrivals((int)$data['arrival_airport_id'], $data['departure_ts'], $data['arrival_ts'], $id);
        if ($depOps >= (int)$depA['pist_sayisi']) throw new RuntimeException('Kalkış havalimanında eşzamanlı operasyon sayısı pist sayısını aşıyor.');
        if ($arrOps >= (int)$arrA['pist_sayisi']) throw new RuntimeException('Varış havalimanında eşzamanlı operasyon sayısı pist sayısını aşıyor.');

        $depConc = $this->concurrentOpsTouching((int)$data['departure_airport_id'], $data['departure_ts'], $data['arrival_ts'], $id);
        $arrConc = $this->concurrentOpsTouching((int)$data['arrival_airport_id'],   $data['departure_ts'], $data['arrival_ts'], $id);
        if ($depConc >= (int)$depA['ucak_kapasitesi']) throw new RuntimeException('Kalkış havalimanında eşzamanlı uçak sayısı kapasiteyi aşıyor.');
        if ($arrConc >= (int)$arrA['ucak_kapasitesi']) throw new RuntimeException('Varış havalimanında eşzamanlı uçak sayısı kapasiteyi aşıyor.');

        $st = $this->pdo->prepare("UPDATE public.flights
            SET departure_airport_id=:da, arrival_airport_id=:aa, plane_id=:p,
                departure_ts=:d, arrival_ts=:a
            WHERE id=:id");
        $st->execute([
            ':da'=>$data['departure_airport_id'],
            ':aa'=>$data['arrival_airport_id'],
            ':p'=>$data['plane_id'],
            ':d'=>$data['departure_ts'],
            ':a'=>$data['arrival_ts'],
            ':id'=>$id,
        ]);
    }
}
