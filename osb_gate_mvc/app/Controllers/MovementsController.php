<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\DB;
use App\Core\Plate;
use App\Core\Time;

final class MovementsController extends Controller
{
    public function index(): void
    {
        $rows = DB::query(<<<'SQL'
            SELECT m.id, m.entry_time, m.exit_time, m.direction,
                   v.plate, p.full_name
            FROM movements m
            LEFT JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN people   p ON p.id = m.person_id
            ORDER BY m.entry_time DESC
            LIMIT 50
        SQL)->fetchAll();

        foreach ($rows as &$r) {
            // Görsel plaka
            $r['plate_tr'] = !empty($r['plate']) ? Plate::toTrDisplay((string)$r['plate']) : '';
            // TR gösterim tarih/saat
            $r['entry_time'] = Time::fmtLocal($r['entry_time'] ?? null, 'Y-m-d H:i');
            $r['exit_time']  = Time::fmtLocal($r['exit_time']  ?? null, 'Y-m-d H:i');
            // Yön etiketi
            if (($r['direction'] ?? '') === 'in') {
                $r['direction_label'] = empty($r['exit_time']) ? 'Giriş' : 'Giriş/Çıkış';
            } else {
                $r['direction_label'] = 'Çıkış';
            }
        }
        unset($r);

        $this->view('movements/index', ['rows' => $rows]);
    }

    public function create(): void
    {
        $this->view('movements/create');
    }

    public function store(): void
    {
        // Plaka normalize
        $plateIntl = (string)($_POST['plate_intl'] ?? '');
        $plateTr   = (string)($_POST['plate_tr']   ?? '');
        $plate     = $plateIntl !== '' ? $plateIntl : Plate::toIntl($plateTr);

        $name         = trim((string)($_POST['person'] ?? ''));
        $dir          = ($_POST['direction'] ?? 'in') === 'out' ? 'out' : 'in';
        $autoExit     = isset($_POST['auto_exit']) && $_POST['auto_exit'] === '1';
        $checkpointId = 1;

        // Saat/dakika (yerel TR → UTC)
        $eh = isset($_POST['entry_hour']) ? (int)$_POST['entry_hour'] : null;
        $em = isset($_POST['entry_min'])  ? (int)$_POST['entry_min']  : null;
        $xh = isset($_POST['exit_hour'])  ? (int)$_POST['exit_hour']  : null;
        $xm = isset($_POST['exit_min'])   ? (int)$_POST['exit_min']   : null;

        $entryAtUtc = Time::localDateHmToUtc($eh, $em, null); // tarih boş => bugün TR
        $exitAtUtc  = Time::localDateHmToUtc($xh, $xm, null);

        $pdo = DB::pdo();
        $pdo->beginTransaction();

        try {
            // Vehicle
            $vehicleId = null;
            if ($plate !== '') {
                $vehicleId = DB::query("SELECT id FROM vehicles WHERE plate = :p", [':p' => $plate])->fetchColumn();
                if (!$vehicleId) {
                    $vehicleId = DB::query("INSERT INTO vehicles(plate) VALUES(:p) RETURNING id", [':p' => $plate])->fetchColumn();
                }
            }

            // Person (opsiyonel)
            $personId = null;
            if ($name !== '') {
                $personId = DB::query("SELECT id FROM people WHERE full_name = :n", [':n' => $name])->fetchColumn();
                if (!$personId) {
                    $personId = DB::query("INSERT INTO people(full_name) VALUES(:n) RETURNING id", [':n' => $name])->fetchColumn();
                }
            }

            if ($dir === 'in') {
                if ($entryAtUtc !== null) {
                    $sql = "INSERT INTO movements(person_id, vehicle_id, checkpoint_id, direction, entry_time)
                            VALUES (:pid, :vid, :cid, 'in', :et) RETURNING id";
                    $params = [':pid' => $personId, ':vid' => $vehicleId, ':cid' => $checkpointId, ':et' => $entryAtUtc];
                } else {
                    $sql = "INSERT INTO movements(person_id, vehicle_id, checkpoint_id, direction, entry_time)
                            VALUES (:pid, :vid, :cid, 'in', now() at time zone 'utc') RETURNING id";
                    $params = [':pid' => $personId, ':vid' => $vehicleId, ':cid' => $checkpointId];
                }
                $movementId = DB::query($sql, $params)->fetchColumn();

                if ($autoExit && $movementId) {
                    if ($exitAtUtc !== null) {
                        DB::query("UPDATE movements SET exit_time = :xt WHERE id = :id", [':xt' => $exitAtUtc, ':id' => $movementId]);
                    } else {
                        DB::query("UPDATE movements SET exit_time = now() at time zone 'utc' WHERE id = :id", [':id' => $movementId]);
                    }
                }
            } else {
                $open = DB::query(<<<'SQL'
                    SELECT id FROM movements
                    WHERE person_id IS NOT DISTINCT FROM :pid
                      AND vehicle_id IS NOT DISTINCT FROM :vid
                      AND direction='in'
                      AND exit_time IS NULL
                    ORDER BY entry_time DESC
                    LIMIT 1
                SQL, [':pid' => $personId, ':vid' => $vehicleId])->fetchColumn();

                if ($open) {
                    if ($exitAtUtc !== null) {
                        DB::query("UPDATE movements SET exit_time = :xt WHERE id = :id", [':xt' => $exitAtUtc, ':id' => $open]);
                    } else {
                        DB::query("UPDATE movements SET exit_time = now() at time zone 'utc' WHERE id = :id", [':id' => $open]);
                    }
                } else {
                    if ($exitAtUtc !== null) {
                        DB::query(
                            "INSERT INTO movements(person_id, vehicle_id, checkpoint_id, direction, exit_time)
                             VALUES (:pid, :vid, :cid, 'out', :xt)",
                            [':pid' => $personId, ':vid' => $vehicleId, ':cid' => $checkpointId, ':xt' => $exitAtUtc]
                        );
                    } else {
                        DB::query(
                            "INSERT INTO movements(person_id, vehicle_id, checkpoint_id, direction, exit_time)
                             VALUES (:pid, :vid, :cid, 'out', now() at time zone 'utc')",
                            [':pid' => $personId, ':vid' => $vehicleId, ':cid' => $checkpointId]
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
