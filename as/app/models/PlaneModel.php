<?php
require_once __DIR__ . '/BaseModel.php';
class PlaneModel extends BaseModel {
    private $table = 'plane';
    // TODO: Add field list and strong-typed methods.
    public function all() { return $this->db->query("SELECT * FROM {".$this->table."}")->fetchAll(); }
}
