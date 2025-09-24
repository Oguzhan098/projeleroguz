<?php
class FlightController {
    public function index() {
        $fm = new FlightModel();
        $am = new AirportModel();
        $pm = new PlaneModel();
        $flights = $fm->allWithJoins();
        $airports = $am->all();
        $planes = $pm->all();
        ob_start();
        extract(['flights'=>$flights, 'airports'=>$airports, 'planes'=>$planes]);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/flights/index.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function create() {
        $am = new AirportModel();
        $pm = new PlaneModel();
        $airports = $am->all();
        $planes = $pm->all();
        ob_start();
        extract(['airports'=>$airports, 'planes'=>$planes]);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/flights/new.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function store() {
        $fm = new FlightModel();
        $fm->create($_POST);
        header('Location: /flights');
        return '';
    }
    public function show($id) {
        $fm = new FlightModel();
        $flight = $fm->find((int)$id);
        $passengers = $fm->passengers((int)$id);
        ob_start(); extract(['flight'=>$flight, 'passengers'=>$passengers]);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/flights/passengers.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function addPassenger($id) {
        $fm = new FlightModel();
        try {
            $fm->addPassenger((int)$id, (int)$_POST['person_id'], $_POST['seat_no'] ?? null, $_POST['ticket_no'] ?? null);
            header('Location: /flights/'.$id);
            return '';
        } catch (Throwable $e) {
            http_response_code(400);
            return "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
