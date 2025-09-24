<?php
class FlightService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 1) Bir havaalanında uçak sayısı, kapasiteyi aşamaz
    public function checkAirportCapacity($airportId) {
        $sql = "SELECT COUNT(*) as sayi, a.ucak_kapasitesi
                FROM planes p
                JOIN flights f ON p.id = f.ucak_id
                JOIN airports a ON a.id = f.havaalani_id
                WHERE a.id = :id
                GROUP BY a.ucak_kapasitesi";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $airportId]);
        $row = $stmt->fetch();
        return $row && $row['sayi'] <= $row['ucak_kapasitesi'];
    }

    // 2) Pist sayısı aşılmasın
    public function checkRunways($airportId, $start, $end) {
        $sql = "SELECT COUNT(*) as aktif, a.pist_sayisi
                FROM flights f
                JOIN airports a ON a.id = f.havaalani_id
                WHERE a.id = :id
                AND ((f.baslangic_zamani, f.bitis_zamani) OVERLAPS (:s, :e))
                GROUP BY a.pist_sayisi";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $airportId, 's' => $start, 'e' => $end]);
        $row = $stmt->fetch();
        return $row && $row['aktif'] < $row['pist_sayisi'];
    }

    // 3) Aynı kişi aynı zaman aralığında uçmasın
    public function checkPassengerConflict($personId, $start, $end) {
        $sql = "SELECT COUNT(*) FROM passenger_flights
                WHERE person_id = :pid
                AND (baslangic_zamani, bitis_zamani) OVERLAPS (:s, :e)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pid' => $personId, 's' => $start, 'e' => $end]);
        return $stmt->fetchColumn() == 0;
    }

    // 4) Aynı uçak aynı anda başka uçuşta olmasın
    public function checkPlaneConflict($planeId, $start, $end) {
        $sql = "SELECT COUNT(*) FROM flights
                WHERE ucak_id = :id
                AND (baslangic_zamani, bitis_zamani) OVERLAPS (:s, :e)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $planeId, 's' => $start, 'e' => $end]);
        return $stmt->fetchColumn() == 0;
    }
}
