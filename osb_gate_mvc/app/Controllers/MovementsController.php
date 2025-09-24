<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Movement;
use App\Models\Vehicle;
use App\Models\Person;

final class MovementsController extends Controller
{
    public function index(): void {
        $list = Movement::latest(50);
        $this->view('movements/index', ['list' => $list]);
    }

    public function create(): void {
        $this->view('movements/create', []);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::check($_POST['_token'] ?? null)) {
            http_response_code(419);
            exit('CSRF token mismatch');
        }
        $plate = trim((string)($_POST['plate'] ?? ''));
        $person = trim((string)($_POST['person'] ?? ''));
        $direction = ($_POST['direction'] ?? 'in') === 'out' ? 'out' : 'in';
        $checkpointId = (int)($_POST['checkpoint_id'] ?? 1);

        $vehicleId = $plate !== '' ? Vehicle::findOrCreateByPlate($plate) : null;
        $personId  = $person !== '' ? Person::findOrCreateByName($person) : null;

        if ($direction === 'in') {
            Movement::createIn($personId, $vehicleId, $checkpointId);
        } else {
            Movement::markOutOrCreate($personId, $vehicleId, $checkpointId);
        }
        $this->redirect('/movements');
    }

    public function scan(): void {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok'=>true, 'message'=>'Scan endpoint placeholder']);
    }
}
