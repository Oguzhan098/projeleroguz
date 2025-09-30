<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Core\Request;
use App\Entities\Custodian;
use App\Entities\Student;
use App\Models\CustodianModel;
use App\Models\StudentModel;
use App\Core\Url;
use App\Core\Helpers;


class CustodiansController extends Controller
{
    private CustodianModel $model;
    private StudentModel $students;

    public function __construct()
    {
        $this->model = new CustodianModel();
        $this->students = new StudentModel();
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

        $custodians = $this->model->searchPaginated($q, $limit, $offset);

        $this->render('custodians/index', [
            'title'    => 'Veliler',
            'custodians' => $custodians,
            'q'        => $q,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $custodians = $this->model->find($id);
        if (!$custodians) {
            Flash::set('error', 'Veli bulunamadı.');
            header('Location: /index.php?r=custodians/index');
            return;
        }
        $this->render('custodians/show', [ 'title' => 'Veli Detayı', 'custodians' => $custodians ]);
    }

    public function create(): void
    {
        $this->render(
            'custodians/create', [ 'title' => 'Yeni Veli', 
                'csrf' => Csrf::token(),
                'students' => $this->students->all()
            ]);
        
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir. Lütfen tekrar deneyin.');
            header('Location: /index.php?r=custodians/create');
            return;
        }


        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old'] = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=custodians/create');
            return;
        }

        $custodians = new Custodian($data['old']['first_name'], $data['old']['last_name']);
        $custodians->student_id = $data['old']['student_id'] ?: null;

        $id = $this->model->create($custodians);
        Flash::set('success', 'Veli oluşturuldu.');
        header('Location: /index.php?r=custodians/show&id=' . $id);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $custodians = $this->model->find($id);
        if (!$custodians) {
            Flash::set('error', 'Veli bulunamadı.');
            header('Location: /index.php?r=custodians/index');
            return;
        }
        $this->render('custodians/edit', [ 'title' => 'Veli Düzenle', 'custodians' => $custodians, 'csrf' => Csrf::token(), 'students' => $this->students->all() ]);
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir.');
            header('Location: /index.php?r=custodians/edit&id=' . $id);
            return;
        }
        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old'] = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=custodians/edit&id=' . $id);
            return;
        }
        $ok = $this->model->update($id, $data['old']);
        $ok ? Flash::set('success', 'Veli güncellendi.') : Flash::set('error', 'Güncelleme başarısız.');
        header('Location: /index.php?r=custodians/show&id=' . $id);
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Güvenlik doğrulaması başarısız.');
            header('Location: /index.php?r=custodians/index');
            return;
        }
        $this->model->delete($id);
        Flash::set('success', 'Veli silindi.');
        header('Location: /index.php?r=custodians/index');
    }

    private function validate(array $input): array
    {
        $first = trim($input['first_name'] ?? '');
        $last  = trim($input['last_name'] ?? '');
        $isd   = (int)($input['student_id'] ?? 0);
        
        $errors = [];
        if ($first === '') $errors['first_name'] = 'Ad zorunlu';
        if ($last === '')  $errors['last_name']  = 'Soyad zorunlu';
        return [
            'old' => ['first_name'=>$first,'last_name'=>$last],
            'errors' => $errors,
            'student_id'=> $isd
        ];
    }
}
