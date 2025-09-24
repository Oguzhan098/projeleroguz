<?php
class Plane {
    public $id;
    public $brand;
    public $model;
    public $capacity;
    public $year;

    public function __construct($brand, $model, $capacity, $year, $id = null) {
        $this->brand = $brand;
        $this->model = $model;
        $this->capacity = $capacity;
        $this->year = $year;
        $this->id = $id;
    }
}
