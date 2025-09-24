<?php
class FlightModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllFlights() {
        $stmt = $this->pdo->query("SELECT * FROM flights ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createFlight($data) {
        $sql = "INSERT INTO flights (nereden, nereye, yolcu_sayisi, baslangic_zamani, bitis_zamani, ucak_id, havaalani_id)
                VALUES (:nereden, :nereye, :yolcu_sayisi, :baslangic, :bitis, :ucak, :havaalani)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nereden'   => $data['nereden'],
            'nereye'    => $data['nereye'],
            'yolcu_sayisi' => $data['yolcu_sayisi'],
            'baslangic' => $data['baslangic_zamani'],
            'bitis'     => $data['bitis_zamani'],
            'ucak'      => $data['ucak_id'],
            'havaalani' => $data['havaalani_id']
        ]);
    }

    public function deleteFlight($id) {
        $stmt = $this->pdo->prepare("DELETE FROM flights WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
