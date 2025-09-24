<?php
require_once __DIR__ . '/../Models/FlightModel.php';

class FlightController {
    private $model;

    public function __construct($pdo) {
        $this->model = new FlightModel($pdo);
    }

    public function index() {
        $flights = $this->model->getAllFlights();
        require __DIR__ . '/../Views/flight.php';
    }

    public function add($data) {
        $this->model->createFlight($data);
        header("Location: /flights");
        exit;
    }

    public function delete($id) {
        $this->model->deleteFlight($id);
        header("Location: /flights");
        exit;
    }
}
