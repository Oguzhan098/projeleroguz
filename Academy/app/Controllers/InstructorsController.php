<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Entities\Instructor;
use App\Models\InstructorModel;

class InstructorsController extends Controller
{
    private InstructorModel $model;

    public function __construct()
    {
        $this->model = new InstructorModel();
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function index(): void
    {
        $instructors = $this->model->all();
        $this->render('instructors/index', [
            'title' => 'Eğitmenler',
            'instructors' => $instructors
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $instructor = $this->model->find($id);
        if (!$instructor) {
            Flash::set('error', 'Eğitmen bulunamadı.');
            header('Location: /index.php?r=instructors/index'); return;
        }
        $this->render('instructors/show', [
            'title' => 'Eğitmen Detayı',
            'instructor' => $instructor
        ]);
    }

    public function create(): void
    {
        $errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
        $old    = $_SESSION['old']    ?? []; unset($_SESSION['old']);

        $this->render('instructors/create', [
            'title'  => 'Yeni Eğitmen',
            'csrf'   => Csrf::token(),
            'errors' => $errors,
            'old'    => $old,
        ]);
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=instructors/create'); return;
        }

        $first = trim((string)($_POST['first_name'] ?? ''));
        $last  = trim((string)($_POST['last_name']  ?? ''));
        $email = trim((string)($_POST['email']      ?? ''));

        $errors = [];
        if ($first === '') $errors['first_name'] = 'Ad zorunlu';
        if ($last  === '') $errors['last_name']  = 'Soyad zorunlu';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli e-posta giriniz';
        }

        if ($errors) {
            $_SESSION['old']    = ['first_name'=>$first,'last_name'=>$last,'email'=>$email];
            $_SESSION['errors'] = $errors;
            header('Location: /index.php?r=instructors/create'); return;
        }

        try {
            $id = $this->model->create(new Instructor($first, $last, $email));
            Flash::set('success', 'Eğitmen oluşturuldu.');
            header('Location: /index.php?r=instructors/show&id='.$id);
        } catch (\PDOException $e) {
            // PostgreSQL UNIQUE ihlali
            if ($e->getCode() === '23505') {
                $_SESSION['errors'] = ['email' => 'Bu e-posta zaten kayıtlı.'];
            } else {
                // Hata ayıklama için logla
                error_log('Instructor store error: '.$e->getCode().' '.$e->getMessage());
                $_SESSION['errors'] = ['general' => 'Kayıt sırasında beklenmeyen hata.'];
            }
            $_SESSION['old'] = ['first_name'=>$first,'last_name'=>$last,'email'=>$email];
            header('Location: /index.php?r=instructors/create'); return;
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $instructor = $this->model->find($id);
        if (!$instructor) {
            Flash::set('error', 'Eğitmen bulunamadı.');
            header('Location: /index.php?r=instructors/index'); return;
        }

        $errors = $_SESSION['errors'] ?? []; unset($_SESSION['errors']);
        $old    = $_SESSION['old']    ?? []; unset($_SESSION['old']);

        $this->render('instructors/edit', [
            'title' => 'Eğitmen Düzenle',
            'instructor' => $instructor,
            'csrf' => Csrf::token(),
            'errors' => $errors,
            'old' => $old,
        ]);
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=instructors/edit&id='.$id); return;
        }

        $first = trim((string)($_POST['first_name'] ?? ''));
        $last  = trim((string)($_POST['last_name']  ?? ''));
        $email = trim((string)($_POST['email']      ?? ''));

        $errors = [];
        if ($first === '') $errors['first_name'] = 'Ad zorunlu';
        if ($last  === '') $errors['last_name']  = 'Soyad zorunlu';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli e-posta giriniz';
        }

        if ($errors) {
            $_SESSION['old']    = ['first_name'=>$first,'last_name'=>$last,'email'=>$email];
            $_SESSION['errors'] = $errors;
            header('Location: /index.php?r=instructors/edit&id='.$id); return;
        }

        try {
            $ok = $this->model->update($id, [
                'first_name'=>$first, 'last_name'=>$last, 'email'=>$email
            ]);
            $ok ? Flash::set('success', 'Eğitmen güncellendi.')
                : Flash::set('error', 'Güncelleme başarısız.');
            header('Location: /index.php?r=instructors/show&id='.$id);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                $_SESSION['errors'] = ['email' => 'Bu e-posta zaten kayıtlı.'];
            } else {
                error_log('Instructor update error: '.$e->getCode().' '.$e->getMessage());
                $_SESSION['errors'] = ['general' => 'Güncelleme sırasında beklenmeyen hata.'];
            }
            $_SESSION['old'] = ['first_name'=>$first,'last_name'=>$last,'email'=>$email];
            header('Location: /index.php?r=instructors/edit&id='.$id); return;
        }
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=instructors/index'); return;
        }
        $this->model->delete($id);
        Flash::set('success', 'Eğitmen silindi.');
        header('Location: /index.php?r=instructors/index');
    }
}
