<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Movement;

final class DashboardController extends Controller
{
    public function index(): void {
        $stats = [
            'inside_people' => Movement::countInside(),
            'today_moves'   => Movement::countToday(),
        ];
        $this->view('dashboard/index', ['stats' => $stats]);
    }

    public function dailyReport(): void {
        $rows = \App\Core\DB::query(<<<'SQL'
            SELECT m.id, m.entry_time, m.exit_time, m.direction, v.plate, p.full_name, c.name as company
            FROM movements m
            LEFT JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN people p ON p.id = m.person_id
            LEFT JOIN companies c ON c.id = p.company_id
            WHERE m.entry_time::date = (now() at time zone 'utc')::date
            ORDER BY m.entry_time DESC
        SQL)->fetchAll();
        $this->view('reports/daily', ['rows' => $rows]);
    }
}
