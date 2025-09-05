<?php
require_once __DIR__ . '/../Models/FlightModel.php';

class FlightController {
    private $model;

    public function __construct($pdo) {
        $this->model = new FlightModel($pdo);
    }

    public function index() {
        $flights = $this->model->getAllFlights();
        $planes = $this->model->getPlanes();
        $airports = $this->model->getAirports();

        include __DIR__ . '/../Views/flight_list.php';
    }

    public function add($data) {
        $this->model->addFlight($data);
    }
}
