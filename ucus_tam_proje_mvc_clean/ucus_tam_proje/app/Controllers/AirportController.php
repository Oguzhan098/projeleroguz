<?php
class AirportController {
    public function index() {
        $model = new AirportModel();
        $airports = $model->all();
        ob_start();
        $data = ['airports'=>$airports];
        extract($data);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/airports/index.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function create() {
        ob_start();
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/airports/new.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function store() {
        $model = new AirportModel();
        $model->create($_POST);
        header('Location: /airports');
        return '';
    }
}
