<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Models\DepartmentModel;
use App\Entities\Department;

class DepartmentsController extends Controller
{
    private DepartmentModel $model;

    public function __construct()
    {
        $this->model = new DepartmentModel();
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    }


    public function index(): void
    {
        $q    = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $per  = 10;

        $total = $this->model->searchCount($q);
        $pages = max(1, (int)ceil($total / $per));
        $page  = min($page, $pages);
        $rows  = $this->model->searchPaginated($q, $per, ($page - 1) * $per);


        $deptIds = array_map(fn($r) => (int)$r['id'], $rows);
        $rels    = $this->model->relationsFor($deptIds, 5);

        $this->render('departments/index', [
            'q' => $q, 'rows' => $rows, 'page' => $page, 'pages' => $pages, 'total' => $total,
            'rels' => $rels,
        ]);
    }


    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $department = $id ? $this->model->find($id) : null;


        $students     = $department ? $this->model->studentsOf($id) : [];
        $instructors  = $department ? $this->model->instructorsOf($id) : [];


        $studentOptions    = $department ? $this->model->candidateStudents($id, 500) : [];
        $instructorOptions = $department ? $this->model->candidateInstructors($id, 500) : [];

        $this->render('departments/show', compact(
            'department', 'students', 'instructors', 'studentOptions', 'instructorOptions'
        ));
    }


    public function create(): void
    {
        $errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
        $old    = $_SESSION['old']    ?? []; unset($_SESSION['old']);
        $this->render('departments/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Güvenlik doğrulaması başarısız.');
            header('Location: /index.php?r=departments/create'); return;
        }

        $data = $this->validateUpsert($_POST);
        if ($data['errors']) {
            $_SESSION['errors'] = $data['errors'];
            $_SESSION['old']    = $data['old'];
            header('Location: /index.php?r=departments/create'); return;
        }

        $d = new Department($data['old']['code'], $data['old']['name'], $data['old']['description'] ?? null);
        try {
            $id = $this->model->create($d);
            Flash::set('success', 'Departman eklendi.');
            header('Location: /index.php?r=departments/show&id='.$id);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                Flash::set('error', 'Kod zaten kayıtlı.');
            } else {
                Flash::set('error', 'Hata: '.$e->getMessage());
            }
            header('Location: /index.php?r=departments/create');
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $department = $id ? $this->model->find($id) : null;
        $errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
        $old    = $_SESSION['old']    ?? []; unset($_SESSION['old']);
        $this->render('departments/edit', compact('department', 'errors', 'old'));
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir.');
            header('Location: /index.php?r=departments/edit&id='.$id); return;
        }

        $data = $this->validateUpsert($_POST);
        if ($data['errors']) {
            $_SESSION['errors'] = $data['errors'];
            $_SESSION['old']    = $data['old'];
            header('Location: /index.php?r=departments/edit&id='.$id); return;
        }

        try {
            $ok = $this->model->update($id, $data['old']);
            $ok ? Flash::set('success', 'Güncellendi.') : Flash::set('error', 'Güncelleme başarısız.');
            header('Location: /index.php?r=departments/show&id='.$id);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                Flash::set('error', 'Kod zaten kayıtlı.');
            } else {
                Flash::set('error', 'Hata: '.$e->getMessage());
            }
            header('Location: /index.php?r=departments/edit&id='.$id);
        }
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Güvenlik doğrulaması başarısız.');
            header('Location: /index.php?r=departments/show&id='.$id); return;
        }

        $ok = $this->model->delete($id);
        $ok ? Flash::set('success', 'Silindi.') : Flash::set('error', 'Silme başarısız.');
        header('Location: /index.php?r=departments/index');
    }

    public function linkStudent(): void
    {
        $id  = (int)($_POST['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş.');
            header('Location: /index.php?r=departments/show&id='.$id); return;
        }
        $sid = (int)($_POST['student_id'] ?? 0);
        if ($id && $sid) { $this->model->linkStudent($id, $sid); }
        header('Location: /index.php?r=departments/show&id='.$id);
    }

    public function linkInstructor(): void
    {
        $id  = (int)($_POST['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş.');
            header('Location: /index.php?r=departments/show&id='.$id); return;
        }
        $iid = (int)($_POST['instructor_id'] ?? 0);
        if ($id && $iid) { $this->model->linkInstructor($id, $iid); }
        header('Location: /index.php?r=departments/show&id='.$id);
    }

    private function validateUpsert(array $input): array
    {
        $code = strtoupper(trim((string)($input['code'] ?? '')));
        $name = trim((string)($input['name'] ?? ''));
        $desc = trim((string)($input['description'] ?? ''));

        $errors = [];

        if (!\App\Entities\Department::isValidCode($code)) {
            $errors['code'] = 'Kod geçersiz (A–Z 0–9 _ -, 2–20).';
        }
        if ($name === '') {
            $errors['name'] = 'Ad zorunlu.';
        }

        return ['old' => ['code' => $code, 'name' => $name, 'description' => $desc], 'errors' => $errors];
    }
}
