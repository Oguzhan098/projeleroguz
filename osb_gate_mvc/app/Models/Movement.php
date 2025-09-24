<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class Movement extends BaseModel
{
    protected static string $table = 'movements';

    /** @return array<int, array<string, mixed>> */
    public static function latest(int $limit = 50): array {
        $sql = <<<SQL
            SELECT m.id, m.entry_time, m.exit_time, m.direction, v.plate, p.full_name, cp.name AS checkpoint
            FROM movements m
            LEFT JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN people p   ON p.id = m.person_id
            LEFT JOIN checkpoints cp ON cp.id = m.checkpoint_id
            ORDER BY m.entry_time DESC
            LIMIT :limit
        SQL;
        $stmt = DB::pdo()->prepare($sql);
        $stmt->bindValue(':limit', \PDO::PARAM_INT, $limit);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT); // PHP8 requires param+type
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countInside(): int {
        $stmt = DB::query("SELECT COUNT(*) FROM movements WHERE direction='in' AND exit_time IS NULL");
        return (int)$stmt->fetchColumn();
    }

    public static function countToday(): int {
        $stmt = DB::query("SELECT COUNT(*) FROM movements WHERE date_trunc('day', entry_time) = date_trunc('day', now() at time zone 'utc')");
        return (int)$stmt->fetchColumn();
    }

    public static function createIn(?int $personId, ?int $vehicleId, int $checkpointId): void {
        $sql = <<<SQL
            INSERT INTO movements(person_id, vehicle_id, checkpoint_id, direction, entry_time)
            VALUES (:pid, :vid, :cid, 'in', now() at time zone 'utc')
        SQL;
        DB::query($sql, [':pid'=>$personId, ':vid'=>$vehicleId, ':cid'=>$checkpointId]);
    }

    public static function markOutOrCreate(?int $personId, ?int $vehicleId, int $checkpointId): void {
        $sql = <<<SQL
            SELECT id FROM movements
            WHERE person_id IS NOT DISTINCT FROM :pid
              AND vehicle_id IS NOT DISTINCT FROM :vid
              AND direction='in' AND exit_time IS NULL
            ORDER BY entry_time DESC
            LIMIT 1
        SQL;
        $open = DB::query($sql, [':pid'=>$personId, ':vid'=>$vehicleId])->fetchColumn();
        if ($open) {
            DB::query("UPDATE movements SET exit_time = now() at time zone 'utc' WHERE id = :id", [':id'=>$open]);
        } else {
            $sql = <<<SQL
                INSERT INTO movements(person_id, vehicle_id, checkpoint_id, direction, exit_time)
                VALUES (:pid, :vid, :cid, 'out', now() at time zone 'utc')
            SQL;
            DB::query($sql, [':pid'=>$personId, ':vid'=>$vehicleId, ':cid'=>$checkpointId]);
        }
    }
}
