<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Core\Request;
use App\Entities\Achievement;
use App\Models\AchievementModel;

class AchievementsController extends \App\Core\Controller
{
    private AchievementModel $model;

    public function __construct()
    {
        $this->model = new AchievementModel(); // Gerekirse PDO enjekte et
    }

    public function index(): void
    {
        $p = $this->buildPaging(function (string $q): int {
            return $this->model->searchCount($q);
        }, 10);

        $achievements = $this->model->searchPaginated($p['q'], $p['limit'], $p['offset']);

        $this->render('achievements/index', [
            'title'        => 'Öğrenciler',
            'achievements' => $achievements,
            'q'            => $p['q'],
            'page'         => $p['page'],
            'pages'        => $p['pages'],
            'total'        => $p['total'],
            'limit'        => $p['limit'],
            'csrf'         => Csrf::token(), // index’te silme formu için
        ]);
    }

    public function show(): void
    {
        $id = (int) Request::get('id', 0);
        $achievement = $this->model->find($id);
        if (!$achievement) {
            Flash::set('error', 'Öğrenci bulunamadı.');
            header('Location: /index.php?r=achievements/index');
            exit;
        }

        $this->render('achievements/show', [
            'title'       => 'Öğrenci Detayı',
            'achievement' => $achievement
        ]);
    }

    public function create(): void
    {
        $this->render('achievements/create', [
            'title' => 'Yeni Öğrenci',
            'csrf'  => Csrf::token()
        ]);
    }

    public function store(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir. Lütfen tekrar deneyin.');
            header('Location: /index.php?r=achievements/create');
            exit;
        }

        $data = $this->validate($_POST);
        if (!empty($data['errors'])) {
            $_SESSION['old']    = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=achievements/create');
            exit;
        }

        $achievement = new Achievement($data['old']['first_name'], $data['old']['last_name']);
        // $achievement->registered_at = null; // gerekiyorsa

        $id = $this->model->create($achievement);

        Flash::set('success', 'Öğrenci oluşturuldu.');
        header('Location: /index.php?r=achievements/show&id=' . $id);
        exit;
    }

    public function edit(): void
    {
        $id = (int) Request::get('id', 0);
        $achievement = $this->model->find($id);
        if (!$achievement) {
            Flash::set('error', 'Öğrenci bulunamadı.');
            header('Location: /index.php?r=achievements/index');
            exit;
        }

        $this->render('achievements/edit', [
            'title'       => 'Öğrenci Düzenle',
            'achievement' => $achievement,
            'csrf'        => Csrf::token()
        ]);
    }

    public function update(): void
    {
        $id = (int) Request::get('id', 0);

        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Oturum süresi dolmuş olabilir.');
            header('Location: /index.php?r=achievements/edit&id=' . $id);
            exit;
        }

        $data = $this->validate($_POST);
        if (!empty($data['errors'])) {
            $_SESSION['old']    = $data['old'];
            $_SESSION['errors'] = $data['errors'];
            header('Location: /index.php?r=achievements/edit&id=' . $id);
            exit;
        }

        $ok = $this->model->update($id, $data['old']);
        $ok ? Flash::set('success', 'Öğrenci güncellendi.') : Flash::set('error', 'Güncelleme başarısız.');

        header('Location: /index.php?r=achievements/show&id=' . $id);
        exit;
    }

    public function delete(): void
    {
        $id = (int) Request::get('id', 0);

        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Güvenlik doğrulaması başarısız.');
            header('Location: /index.php?r=achievements/index');
            exit;
        }

        $this->model->delete($id);
        Flash::set('success', 'Öğrenci silindi.');
        header('Location: /index.php?r=achievements/index');
        exit;
    }


    private function buildPaging(callable $countFn, int $defaultLimit = 10): array
    {
        $q     = trim((string) Request::get('q', ''));
        $page  = max(1, (int) Request::get('page', 1));
        $limit = (int) Request::get('limit', $defaultLimit);
        if ($limit <= 0) { $limit = $defaultLimit; }

        $total = (int) $countFn($q);
        $pages = max(1, (int) ceil($total / $limit));
        $page  = min($page, $pages);
        $offset = ($page - 1) * $limit;

        return compact('q','page','limit','total','pages','offset');
    }

    private function validate(array $input): array
    {
        $first = trim((string)($input['first_name'] ?? ''));
        $last  = trim((string)($input['last_name'] ?? ''));

        $errors = [];
        if ($first === '') { $errors['first_name'] = 'Ad zorunlu'; }
        if ($last  === '') { $errors['last_name']  = 'Soyad zorunlu'; }

        return [
            'old'    => ['first_name' => $first, 'last_name' => $last],
            'errors' => $errors
        ];
    }
}
