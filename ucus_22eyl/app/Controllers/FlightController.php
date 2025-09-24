<?php
require_once __DIR__ . '/../Models/FlightModel.php';
require_once __DIR__ . '/../Models/PersonModel.php';
require_once __DIR__ . '/../Services/FlightService.php';

class FlightController {
    private $flightService;
    private $flightModel;
    private $personModel;

    public function __construct($pdo) {
        $this->flightService = new FlightService($pdo);
        $this->flightModel = new FlightModel($pdo);
        $this->personModel = new PersonModel($pdo);
    }

    public function index() {
        include __DIR__ . '/../Views/add_flight.php';
    }

    public function add($data) {
        // Yolcu oluştur
        $person = new Person($data['first_name'], $data['last_name'], $data['gender'], $data['age']);
        $person_id = $this->personModel->create($person);

        // Zaman formatını PostgreSQL uyumlu yap
        $start_time = date("Y-m-d H:i:s", strtotime($data['start_time']));
        $end_time = date("Y-m-d H:i:s", strtotime($data['end_time']));

        // Çakışma kontrolü
        if (!$this->flightService->checkOverlap($data['airport_id'], $data['plane_id'], $start_time, $end_time)) {
            die("Bu uçak veya havaalanı belirtilen zaman aralığında uygun değil.");
        }

        // Uçuş oluştur
        $flight = new Flight(
            $data['from_city'],
            $data['to_city'],
            $data['passenger_count'],
            $start_time,
            $end_time,
            $data['plane_id'],
            $data['airport_id']
        );

        $this->flightModel->create($flight, $person_id);
        echo "Uçuş başarıyla eklendi!";
    }
}
