<?php

require_once __DIR__ . '/../Models/FlightModel.php';

class FlightController {
    private $model;

    public function __construct($pdo) {
        $this->model = new FlightModel($pdo);
    }

    public function index() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'add_flight') {
                $safe = $this->sanitize($_POST);
                $errors = $this->validate($safe);

                if (empty($errors)) {
                    $this->model->addFlight($safe);
                    $_SESSION['success'] = 'Uçuş başarıyla eklendi.';
                } else {
                    $_SESSION['error'] = implode(' ', $errors);
                }

                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }

            if ($action === 'delete_flight') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->model->deleteFlight($id);
                    $_SESSION['success'] = 'Uçuş silindi.';
                } else {
                    $_SESSION['error'] = 'Geçersiz uçuş ID.';
                }
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }

            if ($action === 'clear_flights') {
                $this->model->clearFlights();
                $_SESSION['success'] = 'Tüm uçuşlar silindi.';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        }

        $flights  = $this->model->getAllFlights();
        $planes   = $this->model->getPlanes();
        $airports = $this->model->getAirports();

        include __DIR__ . '/../Views/flight_list.php';
    }

    private function sanitize(array $d): array {
        return [
            'first_name'      => trim($d['first_name'] ?? ''),
            'last_name'       => trim($d['last_name'] ?? ''),
            'age'             => isset($d['age']) && $d['age'] !== '' ? (int)$d['age'] : null,
            'gender'          => $d['gender'] ?? '',
            'departure'       => trim($d['departure'] ?? ''),
            'arrival'         => trim($d['arrival'] ?? ''),
            'departure_date'  => $d['departure_date'] ?? '',
            'departure_time'  => $d['departure_time'] ?? '',
            'arrival_date'    => $d['arrival_date'] ?? '',
            'arrival_time'    => $d['arrival_time'] ?? '',
            'plane_id'        => isset($d['plane_id']) && $d['plane_id'] !== '' ? (int)$d['plane_id'] : null,
            'airport_id'      => isset($d['airport_id']) && $d['airport_id'] !== '' ? (int)$d['airport_id'] : null,
            'passenger_count' => isset($d['passenger_count']) && $d['passenger_count'] !== '' ? (int)$d['passenger_count'] : 1,
        ];
    }

    private function validate(array $d): array {
        $errors = [];

        if ($d['first_name'] === '') $errors[] = 'İsim gerekli.';
        if ($d['last_name'] === '')  $errors[] = 'Soyisim gerekli.';
        if ($d['departure'] === '')  $errors[] = 'Kalkış gerekli.';
        if ($d['arrival'] === '')    $errors[] = 'Varış gerekli.';
        if ($d['departure_date'] === '') $errors[] = 'Kalkış tarihi gerekli.';
        if ($d['departure_time'] === '') $errors[] = 'Kalkış saati gerekli.';
        if ($d['plane_id'] === null)     $errors[] = 'Uçak seçimi gerekli.';
        if ($d['airport_id'] === null)   $errors[] = 'Havalimanı seçimi gerekli.';

        return $errors;
    }
}
