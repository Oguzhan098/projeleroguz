<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\DB;
use App\Core\Plate;
use App\Core\Time;

final class ReportsController extends Controller
{
    public function redirectDailyToStats(): void
    {
        $today = Time::todayLocal(); // TR bugünün tarihi
        $this->redirect('/reports/stats?start_date=' . $today . '&end_date=' . $today);
    }

    public function stats(): void
    {
        $start = trim((string)($_GET['start_date'] ?? ''));
        $end   = trim((string)($_GET['end_date']   ?? ''));
        $plateTr = trim((string)($_GET['plate'] ?? ''));
        $plateIntl = $plateTr !== '' ? Plate::toIntl($plateTr) : '';

        // Varsayılan tarihler (TR yerel)
        if ($start === '') $start = Time::todayLocal();
        if ($end   === '') $end   = Time::todayLocal();

        // TR yerel gün başı/sonu → UTC
        $tsStart = Time::localDayEdgeToUtc($start, false); // 00:00:00 TR -> UTC
        $tsEnd   = Time::localDayEdgeToUtc($end,   true);  // 23:59:59 TR -> UTC

        $whereIn  = "m.direction='in'  AND m.entry_time BETWEEN :tsStart AND :tsEnd";
        $whereOut = "m.direction='out' AND m.exit_time  BETWEEN :tsStart AND :tsEnd";

        $joinVehicles = "";
        $plateSql = "";
        $params = [
            ':tsStart' => $tsStart,
            ':tsEnd'   => $tsEnd,
        ];

        if ($plateIntl !== '') {
            $joinVehicles = "LEFT JOIN vehicles v ON v.id = m.vehicle_id";
            $plateSql = " AND v.plate = :plate";
            $params[':plate'] = $plateIntl;
        }

        $sqlTotals = "
            SELECT
              (SELECT COUNT(*) FROM movements m $joinVehicles WHERE $whereIn  $plateSql)  AS total_in,
              (SELECT COUNT(*) FROM movements m $joinVehicles WHERE $whereOut $plateSql)  AS total_out,
              (SELECT COUNT(DISTINCT m.vehicle_id) FROM movements m $joinVehicles
                 WHERE ( ($whereIn) OR ($whereOut) ) $plateSql)                          AS distinct_vehicles,
              (SELECT COALESCE(AVG(EXTRACT(EPOCH FROM (m.exit_time - m.entry_time))),0)
                 FROM movements m $joinVehicles
                 WHERE m.exit_time IS NOT NULL
                   AND m.entry_time BETWEEN :tsStart AND :tsEnd
                   $plateSql)                                                          AS avg_dwell_secs
        ";
        $totals = DB::query($sqlTotals, $params)->fetch();

        $sqlByHour = "
            SELECT date_trunc('hour', m.entry_time) AS hour_utc,
                   COUNT(*) AS cnt
            FROM movements m
            $joinVehicles
            WHERE $whereIn $plateSql
            GROUP BY 1
            ORDER BY 1
        ";
        $byHourRaw = DB::query($sqlByHour, $params)->fetchAll();

        // UTC hour -> TR label
        $byHour = [];
        foreach ($byHourRaw as $row) {
            $byHour[] = [
                'hour_label' => Time::fmtLocal($row['hour_utc'] ?? null, 'Y-m-d H:00'),
                'cnt'        => (int)$row['cnt'],
            ];
        }

        $sqlList = "
            SELECT m.id, m.entry_time, m.exit_time, m.direction,
                   v.plate, p.full_name
            FROM movements m
            LEFT JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN people   p ON p.id = m.person_id
            WHERE
                 ( (m.direction='in'  AND m.entry_time BETWEEN :tsStart AND :tsEnd)
                OR (m.direction='out' AND m.exit_time  BETWEEN :tsStart AND :tsEnd) )
            " . ($plateIntl !== '' ? " AND v.plate = :plate " : "") . "
            ORDER BY COALESCE(m.exit_time, m.entry_time) DESC
            LIMIT 100
        ";
        $rows = DB::query($sqlList, $params)->fetchAll();

        foreach ($rows as &$r) {
            $r['plate_tr']   = !empty($r['plate']) ? Plate::toTrDisplay((string)$r['plate']) : '';
            $r['entry_time'] = Time::fmtLocal($r['entry_time'] ?? null, 'Y-m-d H:i');
            $r['exit_time']  = Time::fmtLocal($r['exit_time']  ?? null, 'Y-m-d H:i');
            if (($r['direction'] ?? '') === 'in') {
                $r['direction_label'] = empty($r['exit_time']) ? 'Giriş' : 'Giriş/Çıkış';
            } else {
                $r['direction_label'] = 'Çıkış';
            }
        }
        unset($r);

        $avgMinutes = 0;
        if (isset($totals['avg_dwell_secs'])) {
            $avgMinutes = (int) round(((float)$totals['avg_dwell_secs']) / 60.0);
        }

        $this->view('reports/stats', [
            'start_date' => $start,
            'end_date'   => $end,
            'plate'      => $plateTr,
            'totals'     => $totals,
            'avg_minutes'=> $avgMinutes,
            'byHour'     => $byHour,
            'rows'       => $rows,
        ]);
    }
}
