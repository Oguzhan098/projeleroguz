<?php
class PeopleController {
    public function index() {
        $model = new PeopleModel();
        $people = $model->all();
        ob_start(); extract(['people'=>$people]);
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/people/index.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function create() {
        ob_start();
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/people/new.php';
        include __DIR__ . '/../views/layout/footer.php';
        return ob_get_clean();
    }
    public function store() {
        $model = new PeopleModel();
        $model->create($_POST);
        header('Location: /people');
        return '';
    }
}
