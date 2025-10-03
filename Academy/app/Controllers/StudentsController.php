<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Core\Request;
use App\Entities\Student;
use App\Models\StudentModel;
use App\Core\Url;
use App\Core\Helpers;
use App\Core\Validator;
use App\Core\Router;
use App\Core\Database;
use App\Core\Model;

class StudentsController extends Controller
{
    private StudentModel $model;

    public function __construct()
    {
        $this->model = new StudentModel();
    }

    public function index(): void
    {
        $q     = trim((string)Request::get('q', ''));
        $page  = max(1, (int)Request::get('page', 1));
        $limit = 10;
        $total = $this->model->searchCount($q);
        $pages = max(1, (int)ceil($total / $limit));
        $page  = min($page, $pages);
        $offset = ($page - 1) * $limit;

        $students = $this->model->searchPaginated($q, $limit, $offset);

        $this->render('students/index', [
            'title'    => 'Öğrenciler',
            'students' => $students,
            'q'        => $q,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $student = $this->model->find($id);
        if (!$student) {
            Flash::set('error', 'Öğrenci bulunamadı.');
            header('Location: /index.php?r=students/index');
            return;
        }
        $this->render('students/show', [ 'title' => 'Öğrenci Detayı', 'student' => $student ]);
    }

    public function create(): void
    {
        $this->render('students/create', [ 'title' => 'Yeni Öğrenci', 'csrf' => Csrf::token() ]);
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir. Lütfen tekrar deneyin.');
            header('Location: /index.php?r=students/create');
            return;
        }


       $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old'] = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=students/create');
            return;
        }

        $student = new Student($data['old']['first_name'], $data['old']['last_name'], $data['old']['email']);
        $id = $this->model->create($student);
        Flash::set('success', 'Öğrenci oluşturuldu.');
        header('Location: /index.php?r=students/show&id=' . $id);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $student = $this->model->find($id);
        if (!$student) {
            Flash::set('error', 'Öğrenci bulunamadı.');
            header('Location: /index.php?r=students/index');
            return;
        }
        $this->render('students/edit', [ 'title' => 'Öğrenci Düzenle', 'student' => $student, 'csrf' => Csrf::token() ]);
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir.');
            header('Location: /index.php?r=students/edit&id=' . $id);
            return;
        }
        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old'] = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=students/edit&id=' . $id);
            return;
        }
        $ok = $this->model->update($id, $data['old']);
        $ok ? Flash::set('success', 'Öğrenci güncellendi.') : Flash::set('error', 'Güncelleme başarısız.');
        header('Location: /index.php?r=students/show&id=' . $id);
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Güvenlik doğrulaması başarısız.');
            header('Location: /index.php?r=students/index');
            return;
        }
        $this->model->delete($id);
        Flash::set('success', 'Öğrenci silindi.');
        header('Location: /index.php?r=students/index');
    }

    private function validate(array $input): array
    {
        $first = trim($input['first_name'] ?? '');
        $last  = trim($input['last_name'] ?? '');
        $email = trim($input['email'] ?? '');

        $errors = [];
        if ($first === '') $errors['first_name'] = 'Ad zorunlu';
        if ($last === '')  $errors['last_name']  = 'Soyad zorunlu';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli e-posta giriniz';
        }
        return [
            'old' => ['first_name'=>$first,'last_name'=>$last,'email'=>$email],
            'errors' => $errors
        ];
    }
}
