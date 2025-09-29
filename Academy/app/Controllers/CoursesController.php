<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Entities\Course;
use App\Models\CourseModel;
use App\Models\InstructorModel;

class CoursesController extends Controller
{
    private CourseModel $courses;
    private InstructorModel $instructors;

    public function __construct()
    {
        $this->courses     = new CourseModel();
        $this->instructors = new InstructorModel();
    }

    public function index(): void
    {
        $courses = $this->courses->all();
        $this->render('courses/index', [
            'title'   => 'Dersler',
            'courses' => $courses
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $course = $this->courses->find($id);
        if (!$course) {
            Flash::set('error', 'Ders bulunamadı.');
            header('Location: /index.php?r=courses/index');
            return;
        }
        $this->render('courses/show', ['title' => 'Ders Detayı', 'course' => $course]);
    }

    public function create(): void
    {
        $this->render('courses/create', [
            'title'       => 'Yeni Ders',
            'csrf'        => Csrf::token(),
            'instructors' => $this->instructors->all()
        ]);
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=courses/create');
            return;
        }

        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old']    = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=courses/create');
            return;
        }

        $course = new Course($data['old']['code'], $data['old']['title']);
        $course->description  = $data['old']['description'];
        $course->instructor_id = $data['old']['instructor_id'] ?: null;

        $id = $this->courses->create($course);
        Flash::set('success', 'Ders oluşturuldu.');
        header('Location: /index.php?r=courses/show&id=' . $id);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $course = $this->courses->find($id);
        if (!$course) {
            Flash::set('error', 'Ders bulunamadı.');
            header('Location: /index.php?r=courses/index');
            return;
        }
        $this->render('courses/edit', [
            'title'       => 'Ders Düzenle',
            'course'      => $course,
            'csrf'        => Csrf::token(),
            'instructors' => $this->instructors->all()
        ]);
    }

    public function update(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=courses/edit&id=' . $id);
            return;
        }

        $data = $this->validate($_POST);
        if ($data['errors']) {
            $_SESSION['old']    = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=courses/edit&id=' . $id);
            return;
        }

        $ok = $this->courses->update($id, $data['old']);
        $ok ? Flash::set('success', 'Ders güncellendi.') : Flash::set('error', 'Güncelleme hatası.');
        header('Location: /index.php?r=courses/show&id=' . $id);
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'CSRF hatası.');
            header('Location: /index.php?r=courses/index');
            return;
        }
        $this->courses->delete($id);
        Flash::set('success', 'Ders silindi.');
        header('Location: /index.php?r=courses/index');
    }

    private function validate(array $input): array
    {
        $code  = trim($input['code'] ?? '');
        $title = trim($input['title'] ?? '');
        $desc  = trim($input['description'] ?? '');
        $iid   = (int)($input['instructor_id'] ?? 0);

        $errors = [];
        if ($code === '')  $errors['code'] = 'Kod gerekli';
        if ($title === '') $errors['title'] = 'Başlık gerekli';

        return [
            'old' => [
                'code'         => $code,
                'title'        => $title,
                'description'  => $desc,
                'instructor_id'=> $iid
            ],
            'errors' => $errors
        ];
    }
}
