<?php
class Person {
    public $id;
    public $first_name;
    public $last_name;
    public $gender;
    public $age;

    public function __construct($first_name, $last_name, $gender, $age, $id = null) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->gender = $gender;
        $this->age = $age;
        $this->id = $id;
    }
}
