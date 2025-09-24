<?php
require_once __DIR__ . '/BaseModel.php';
class AirportModel extends BaseModel {
    private $table = 'airport';
    // TODO: Add field list and strong-typed methods.
    public function all() { return $this->db->query("SELECT * FROM {".$this->table."}")->fetchAll(); }
}
