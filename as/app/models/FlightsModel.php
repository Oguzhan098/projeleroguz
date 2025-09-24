<?php
require_once __DIR__ . '/BaseModel.php';
class FlightsModel extends BaseModel {
    private $table = 'flights';
    // TODO: Add field list and strong-typed methods.
    public function all() { return $this->db->query("SELECT * FROM {".$this->table."}")->fetchAll(); }
}
