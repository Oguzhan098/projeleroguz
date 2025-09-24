<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

abstract class BaseModel
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    /** @return array<int, array<string, mixed>> */
    public static function all(int $limit = 100, int $offset = 0): array {
        $sql = sprintf('SELECT * FROM %s ORDER BY %s DESC LIMIT :limit OFFSET :offset', static::$table, static::$primaryKey);
        $stmt = DB::pdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public static function find(int $id): ?array {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :id', static::$table, static::$primaryKey);
        $row = DB::query($sql, [':id'=>$id])->fetch();
        return $row ?: null;
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int {
        $cols = array_keys($data);
        $params = array_map(fn($c) => ':' . $c, $cols);
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s) RETURNING %s',
            static::$table,
            implode(',', $cols),
            implode(',', $params),
            static::$primaryKey
        );
        $stmt = DB::pdo()->prepare($sql);
        foreach ($data as $k=>$v) { $stmt->bindValue(':' . $k, $v); }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): void {
        $sets = [];
        foreach ($data as $k => $_) { $sets[] = $k . ' = :' . $k; }
        $sql = sprintf('UPDATE %s SET %s WHERE %s = :id',
            static::$table,
            implode(', ', $sets),
            static::$primaryKey
        );
        $stmt = DB::pdo()->prepare($sql);
        foreach ($data as $k=>$v) { $stmt->bindValue(':' . $k, $v); }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function delete(int $id): void {
        $sql = sprintf('DELETE FROM %s WHERE %s = :id', static::$table, static::$primaryKey);
        DB::query($sql, [':id'=>$id]);
    }

    /** @param array<string, mixed> $where
     *  @return array<int, array<string, mixed>>
     */
    public static function where(array $where, int $limit = 100): array {
        $conds = [];
        $params = [];
        foreach ($where as $k=>$v) {
            if ($v === null) {
                $conds[] = "$k IS NULL";
            } else {
                $conds[] = "$k = :$k";
                $params[":$k"] = $v;
            }
        }
        $sql = sprintf('SELECT * FROM %s%s ORDER BY %s DESC LIMIT %d',
            static::$table,
            $conds ? (' WHERE ' . implode(' AND ', $conds)) : '',
            static::$primaryKey,
            $limit
        );
        $stmt = DB::pdo()->prepare($sql);
        foreach ($params as $p=>$v) { $stmt->bindValue($p, $v); }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @param array<string, mixed> $where
     *  @return array<string, mixed>|null
     */
    public static function first(array $where): ?array {
        $rows = static::where($where, 1);
        return $rows[0] ?? null;
    }
}
