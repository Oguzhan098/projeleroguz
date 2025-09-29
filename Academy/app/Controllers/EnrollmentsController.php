<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Entities\Enrollment;
use App\Models\EnrollmentModel;
use App\Models\StudentModel;
use App\Models\CourseModel;
use PDOException;

class EnrollmentsController extends Controller
{
    private EnrollmentModel $enrollments;
    private StudentModel $students;
    private CourseModel $courses;

    public function __construct()
    {
        $this->enrollments = new EnrollmentModel();
        $this->students    = new StudentModel();
        $this->courses     = new CourseModel();
    }

    // Liste
    public function index(): void
    {
        $data = $this->enrollments->all();
        $this->render('enrollments/index', [
            'title' => 'Kayıtlar',
            'enrollments' => $data
        ]);
    }

    // Yeni kayıt formu
    public function create(): void
    {
        $this->render('enrollments/create', [
            'title'    => 'Öğrenciyi Derse Kaydet',
            'csrf'     => Csrf::token(),
            'students' => $this->students->all(),
            'courses'  => $this->courses->all(),
        ]);
    }

    // Kayıt oluştur
    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=enrollments/create'); return;
        }

        $sid = (int)($_POST['student_id'] ?? 0);
        $cid = (int)($_POST['course_id'] ?? 0);
        $grade = isset($_POST['grade']) && $_POST['grade'] !== '' ? (float)$_POST['grade'] : null;

        if ($sid <= 0 || $cid <= 0) {
            Flash::set('error', 'Öğrenci ve ders seçiniz.');
            header('Location: /index.php?r=enrollments/create'); return;
        }

        try {
            $e = new Enrollment($sid, $cid);
            $e->grade = $grade;
            $id = $this->enrollments->create($e);
            Flash::set('success', 'Kayıt eklendi.');
            header('Location: /index.php?r=enrollments/index');
        } catch (PDOException $ex) {
            // PostgreSQL unique violation: 23505
            if ($ex->getCode() === '23505') {
                Flash::set('error', 'Bu öğrenci zaten bu derse kayıtlı.');
            } else {
                Flash::set('error', 'Kayıt ekleme hatası: ' . $ex->getMessage());
            }
            header('Location: /index.php?r=enrollments/create');
        }
    }

    // Not güncelle (inline)
    public function updateGrade(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=enrollments/index'); return;
        }
        $id = (int)($_GET['id'] ?? 0);
        $grade = ($_POST['grade'] === '' ? null : (float)$_POST['grade']);
        $ok = $this->enrollments->updateGrade($id, $grade);
        $ok ? Flash::set('success', 'Not güncellendi.') : Flash::set('error', 'Güncelleme başarısız.');
        header('Location: /index.php?r=enrollments/index');
    }

    // Sil
    public function delete(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=enrollments/index'); return;
        }
        $id = (int)($_GET['id'] ?? 0);
        $this->enrollments->delete($id);
        Flash::set('success', 'Kayıt silindi.');
        header('Location: /index.php?r=enrollments/index');
    }
}
