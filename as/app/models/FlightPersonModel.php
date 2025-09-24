<?php
require_once __DIR__ . '/BaseModel.php';
class FlightPersonModel extends BaseModel {
    private $table = 'flight_person';
    // TODO: Add field list and strong-typed methods.
    public function all() { return $this->db->query("SELECT * FROM {".$this->table."}")->fetchAll(); }
}
