<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\DB;

final class MovementsController extends Controller
{
    public function index(): void {
        $rows = DB::query(<<<'SQL'
            SELECT m.id, m.entry_time, m.exit_time, m.direction,
                   v.plate, p.full_name
            FROM movements m
            LEFT JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN people   p ON p.id = m.person_id
            ORDER BY m.entry_time DESC
            LIMIT 50
        SQL)->fetchAll();
        $this->view('movements/index', ['rows'=>$rows]);
    }

    public function create(): void {
        $this->view('movements/create');
    }

    public function store(): void {
        $plate = trim((string)($_POST['plate'] ?? ''));
        $name  = trim((string)($_POST['person'] ?? ''));
        $dir   = ($_POST['direction'] ?? 'in') === 'out' ? 'out' : 'in';
        $autoExit = isset($_POST['auto_exit']) && $_POST['auto_exit'] === '1';
        $checkpointId = 1;

        // Formdan saat/dakika (boş ise null)
        $eh = isset($_POST['entry_hour']) ? (int)$_POST['entry_hour'] : null;
        $em = isset($_POST['entry_min'])  ? (int)$_POST['entry_min']  : null;
        $xh = isset($_POST['exit_hour'])  ? (int)$_POST['exit_hour']  : null;
        $xm = isset($_POST['exit_min'])   ? (int)$_POST['exit_min']   : null;

        // Bugünün tarihi (UTC) baz alınır; istersen .env ile TZ alıp dönüştürebiliriz
        $todayUtc = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateStr  = $todayUtc->format('Y-m-d');

        // Yardımcı: saat/dk verildiyse "Y-m-d H:i:00" üret, yoksa null
        $mkTime = function (?int $hour, ?int $min) use ($dateStr): ?string {
            if ($hour === null || $min === null) return null;
            $h = str_pad((string)max(0, min(23, $hour)), 2, '0', STR_PAD_LEFT);
            $m = str_pad((string)max(0, min(59, $min )), 2, '0', STR_PAD_LEFT);
            return $dateStr . ' ' . $h . ':' . $m . ':00';
        };

        $entryAt = $mkTime($eh, $em);  // giriş zamanı (opsiyonel)
        $exitAt  = $mkTime($xh, $xm);  // çıkış zamanı (opsiyonel)

        $pdo = DB::pdo();
        $pdo->beginTransaction();
        try {
            // Vehicle / Person find-or-create
            $vehicleId = null;
            if ($plate !== '') {
                $vehicleId = DB::query("SELECT id FROM vehicles WHERE plate=:p", [':p'=>$plate])->fetchColumn();
                if (!$vehicleId) {
                    $vehicleId = DB::query("INSERT INTO vehicles(plate) VALUES(:p) RETURNING id", [':p'=>$plate])->fetchColumn();
                }
            }

            $personId = null;
            if ($name !== '') {
                $personId = DB::query("SELECT id FROM people WHERE full_name=:n", [':n'=>$name])->fetchColumn();
                if (!$personId) {
                    $personId = DB::query("INSERT INTO people(full_name) VALUES(:n) RETURNING id", [':n'=>$name])->fetchColumn();
                }
            }

            if ($dir === 'in') {
                // GİRİŞ: verilen saat varsa onu kullan, yoksa now() (UTC)
                if ($entryAt) {
                    $movementId = DB::query(
                        "INSERT INTO movements(person_id,vehicle_id,checkpoint_id,direction,entry_time)
                         VALUES (:pid,:vid,:cid,'in', :et) RETURNING id",
                        [':pid'=>$personId, ':vid'=>$vehicleId, ':cid'=>$checkpointId, ':et'=>$entryAt]
                    )->fetchColumn();
                } else {
                    $movementId = DB::query(
                        "INSERT INTO movements(person_id,vehicle_id,checkpoint_id,direction,entry_time)
                         VALUES (:pid,:vid,:cid,'in', now() at time zone 'utc') RETURNING id",
                        [':pid'=>$personId, ':vid'=>$vehicleId, ':cid'=>$checkpointId]
                    )->fetchColumn();
                }

                // Otomatik kapatma istendiyse çıkış zamanını da kullan (verilmişse onu, yoksa now())
                if ($autoExit && $movementId) {
                    if ($exitAt) {
                        DB::query(
                            "UPDATE movements SET exit_time = :xt WHERE id = :id",
                            [':xt'=>$exitAt, ':id'=>$movementId]
                        );
                    } else {
                        DB::query(
                            "UPDATE movements SET exit_time = now() at time zone 'utc' WHERE id = :id",
                            [':id'=>$movementId]
                        );
                    }
                }

            } else {
                // ÇIKIŞ: açık giriş var mı?
                $open = DB::query(<<<'SQL'
                    SELECT id FROM movements
                    WHERE person_id IS NOT DISTINCT FROM :pid
                      AND vehicle_id IS NOT DISTINCT FROM :vid
                      AND direction='in' AND exit_time IS NULL
                    ORDER BY entry_time DESC LIMIT 1
                SQL, [':pid'=>$personId, ':vid'=>$vehicleId])->fetchColumn();

                if ($open) {
                    if ($exitAt) {
                        DB::query("UPDATE movements SET exit_time = :xt WHERE id = :id", [':xt'=>$exitAt, ':id'=>$open]);
                    } else {
                        DB::query("UPDATE movements SET exit_time = now() at time zone 'utc' WHERE id = :id", [':id'=>$open]);
                    }
                } else {
                    // Tek başına çıkış kaydı
                    if ($exitAt) {
                        DB::query(
                            "INSERT INTO movements(person_id,vehicle_id,checkpoint_id,direction,exit_time)
                             VALUES (:pid,:vid,:cid,'out', :xt)",
                            [':pid'=>$personId, ':vid'=>$vehicleId, ':cid'=>$checkpointId, ':xt'=>$exitAt]
                        );
                    } else {
                        DB::query(
                            "INSERT INTO movements(person_id,vehicle_id,checkpoint_id,direction,exit_time)
                             VALUES (:pid,:vid,:cid,'out', now() at time zone 'utc')",
                            [':pid'=>$personId, ':vid'=>$vehicleId, ':cid'=>$checkpointId]
                        );
                    }
                }
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            http_response_code(500);
            exit('DB error: ' . $e->getMessage());
        }

        $this->redirect('/movements');
    }
}
