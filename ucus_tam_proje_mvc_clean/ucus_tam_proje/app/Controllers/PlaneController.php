<?php
class PlaneController {
    public function index() {
        $model = new PlaneModel();
        $planes = $model->all();
        ob_start(); extract(['planes'=>$planes]);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/planes/index.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function create() {
        ob_start();
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/planes/new.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function store() {
        $model = new PlaneModel();
        $model->create($_POST);
        header('Location: /planes');
        return '';
    }
}
