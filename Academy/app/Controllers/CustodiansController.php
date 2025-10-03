<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Core\Request;
use App\Entities\Custodian;
use App\Models\CustodianModel;
use App\Models\StudentModel;


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
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $per  = 10;
        $total = $this->model->searchCount($q);
        $pages = max(1, (int)ceil($total / $per));
        $page  = min($page, $pages);
        $rows  = $this->model->searchPaginated($q, $per, ($page-1)*$per);


        $deptIds = array_map(fn($r) => (int)$r['id'], $rows);
        $rels = $this->model->relationsFor($deptIds, 5);

        $this->render('departments/index', [
            'q' => $q,
            'rows' => $rows,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
            'rels' => $rels,
            ]  );
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
        $this->render('custodians/show',
            [ 'title' => 'Veli Detayı', 'custodians' => $custodians ]);
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
            $_SESSION['old']    = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=custodians/create');
            return;
        }

        $custodians = new Custodian($data['old']['first_name'], $data['old']['last_name']);
        $custodians->student_id = $data['old']['student_id'] ?: null; // artık set oluyor

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
            $_SESSION['old']    = $data['old'];
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
            'old'    => ['first_name' => $first, 'last_name' => $last, 'student_id' => $isd],
            'errors' => $errors,
        ];
    }
}
