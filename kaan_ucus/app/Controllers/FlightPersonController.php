<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\FlightPerson;

final class FlightPersonController extends Controller
{
    public function attach(): void {
        $flightId = (int)self::input('flight_id');
        $personId = (int)self::input('person_id');

        if ($flightId <= 0 || $personId <= 0) {
            http_response_code(400);
            echo "Eksik parametre. Örnek: /flight-person/attach?flight_id=3&person_id=2";
            return;
        }

        try {
            (new FlightPerson())->attach($flightId, $personId);
            $this->redirect('/flights/' . $flightId . '/edit');
        } catch (\Throwable $e) {
            http_response_code(400);
            echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
