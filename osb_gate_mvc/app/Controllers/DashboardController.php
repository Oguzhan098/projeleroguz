<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\DB;

final class DashboardController extends Controller
{
    public function index(): void {
        $inside = (int) DB::query("SELECT COUNT(*) FROM movements WHERE direction='in' AND exit_time IS NULL")->fetchColumn();
        $today  = (int) DB::query("SELECT COUNT(*) FROM movements WHERE entry_time::date = (now() at time zone 'utc')::date")->fetchColumn();
        $this->view('dashboard/index', ['inside'=>$inside, 'today'=>$today]);
    }

    public function dailyReport(): void {
        $rows = DB::query(<<<'SQL'
            SELECT m.id, m.entry_time, m.exit_time, m.direction, v.plate, p.full_name
            FROM movements m
            LEFT JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN people   p ON p.id = m.person_id
            WHERE m.entry_time::date = (now() at time zone 'utc')::date
            ORDER BY m.entry_time DESC
        SQL)->fetchAll();
        $this->view('reports/daily', ['rows'=>$rows]);
    }
}
