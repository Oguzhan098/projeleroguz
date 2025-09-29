<?php
namespace App\Controllers;

use App\{Core\Controller,
    Core\Flash,
    Core\Csrf,
    Core\Request,
    Entities\Custodian,
    Models\CustodianModel,
    Core\Url,
    Core\Helpers};

class CustodianController extends Controller
{
    private CustodianModel $model;

    public function __construct()
    {
        $this->model = new CustodianModel();
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

        $custodian = $this->model->searchPaginated($q, $limit, $offset);

        $this->render('custodian/index', [
            'title'    => 'Veliler',
            'custodian' => $custodian,
            'q'        => $q,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $custodian = $this->model->find($id);
        if (!$custodian) {
            Flash::set('error', 'Veli bulunamadı.');
            header('Location: /index.php?r=custodians/index');
            return;
        }
        $this->render('custodian/show', [ 'title' => 'Veli Detayı', 'custodian' => $custodian ]);
    }

    public function create(): void
    {
        $this->render('custodian/create', [ 'title' => 'Yeni Veli', 'csrf' => Csrf::token() ]);
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir. Lütfen tekrar deneyin.');
            header('Location: /index.php?r=custodian/create');
            return;
        }


        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old'] = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=custodian/create');
            return;
        }

        $custodian = new Custodian($data['old']['first_name'], $data['old']['last_name'], $data['old']['email']);
        $id = $this->model->create($custodian);
        Flash::set('success', 'Veli oluşturuldu.');
        header('Location: /index.php?r=custodian/show&id=' . $id);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $custodian = $this->model->find($id);
        if (!$custodian) {
            Flash::set('error', 'Veli bulunamadı.');
            header('Location: /index.php?r=custodians/index');
            return;
        }
        $this->render('custodian/edit', [ 'title' => 'Veli Düzenle', 'custodian' => $custodian, 'csrf' => Csrf::token() ]);
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir.');
            header('Location: /index.php?r=custodian/edit&id=' . $id);
            return;
        }
        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old'] = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=custodian/edit&id=' . $id);
            return;
        }
        $ok = $this->model->update($id, $data['old']);
        $ok ? Flash::set('success', 'Veli güncellendi.') : Flash::set('error', 'Güncelleme başarısız.');
        header('Location: /index.php?r=custodian/show&id=' . $id);
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
