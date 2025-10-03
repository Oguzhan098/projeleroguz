<?php
namespace App\Models;


use App\Core\Model;
use App\Entities\Custodian;
use PDO;


class CustodianModel extends Model
{
    public function all(): array
    {
        $sql = "
        SELECT 
            c.*,
            i.first_name AS student_first,
            i.last_name  AS student_last,
            COALESCE(TRIM(i.first_name || ' ' || i.last_name), '') AS student_full
        FROM custodians c
        LEFT JOIN students i ON i.id = c.student_id
        ORDER BY c.id DESC
    ";
        return $this->db->query($sql)->fetchAll();
    }


    public function find(int $id): ?array
    {
        $sql = "
        SELECT
            c.*,
            i.first_name AS student_first,
            i.last_name  AS student_last,
            COALESCE(TRIM(i.first_name || ' ' || i.last_name), '') AS student_full
        FROM custodians c
        LEFT JOIN students i ON i.id = c.student_id
        WHERE c.id = :id
        LIMIT 1
    ";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id', $id, \PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        return $row ?: null;
    }



    public function create(Custodian $s): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO custodians(first_name,last_name,student_id) 
                    VALUES(:fn,:ln,:isd) RETURNING id');
        $stmt->execute(['fn'=>$s->first_name,'ln'=>$s->last_name,'isd'=>$s->student_id]);
        return (int)$stmt->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE custodians 
                    SET first_name=:fn,last_name=:ln,updated_at=NOW(),student_id=:isd 
                    WHERE id=:id');
        return $stmt->execute([
            'fn'=>$data['first_name'],
            'ln'=>$data['last_name'],
            'isd'=>$data['student_id']??null,
            'id'=>$id
        ]);
    }


    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM custodians WHERE id=:id');
        return $stmt->execute(['id'=>$id]);
    }

    public function searchCount(string $q = ''): int
    {
        if ($q === '') {
            return (int)$this->db->query('SELECT COUNT(*) FROM custodians')->fetchColumn();
        }

        $sql = "SELECT COUNT(DISTINCT c.id)
            FROM custodians c
            LEFT JOIN students i ON i.id = c.student_id
            WHERE (c.first_name ILIKE :q OR c.last_name ILIKE :q
                   OR i.first_name ILIKE :q OR i.last_name ILIKE :q)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':q', '%'.$q.'%');
        $st->execute();
        return (int)$st->fetchColumn();
    }


    public function searchPaginated(string $q = '', int $limit = 10, int $offset = 0): array
    {
        $limit  = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        $baseSelect = "
        SELECT
            c.*,
            i.first_name AS student_first,
            i.last_name  AS student_last,
            COALESCE(TRIM(i.first_name || ' ' || i.last_name), '') AS student_full
        FROM custodians c
        LEFT JOIN students i ON i.id = c.student_id
    ";

        if ($q === '') {
            $sql = "$baseSelect
                ORDER BY c.id DESC
                LIMIT $limit OFFSET $offset";
            $st = $this->db->prepare($sql);
            $st->execute();
            return $st->fetchAll();
        }

        $sql = "$baseSelect
            WHERE (c.first_name ILIKE :q OR c.last_name ILIKE :q
                   OR i.first_name ILIKE :q OR i.last_name ILIKE :q)
            ORDER BY c.id DESC
            LIMIT $limit OFFSET $offset";
        $st = $this->db->prepare($sql);
        $st->bindValue(':q', '%'.$q.'%');
        $st->execute();
        return $st->fetchAll();
    }
    public function relationsFor(array $deptIds, int $limitPerType = 5): array
    {
        if (empty($deptIds)) {
            return [
                'students' => [], 'students_count' => [],
                'instructors' => [], 'instructors_count' => [],
            ];
        }

        // IN klausulü için placeholder üret
        $params = [];
        $ph = [];
        foreach ($deptIds as $i => $id) {
            $key = ':d' . $i;
            $params[$key] = (int)$id;
            $ph[] = $key;
        }
        $inClause = '(' . implode(',', $ph) . ')';

        $sqlCntStu = "SELECT department_id, COUNT(*) AS cnt
                  FROM students
                  WHERE department_id IN $inClause
                  GROUP BY department_id";
        $st = $this->db->prepare($sqlCntStu);
        foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->execute();
        $students_count = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $students_count[(int)$r['department_id']] = (int)$r['cnt'];
        }

        $sqlCntIns = "SELECT department_id, COUNT(*) AS cnt
                  FROM instructors
                  WHERE department_id IN $inClause
                  GROUP BY department_id";
        $st = $this->db->prepare($sqlCntIns);
        foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->execute();
        $instructors_count = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $instructors_count[(int)$r['department_id']] = (int)$r['cnt'];
        }

        $sqlStu = "
      SELECT id, first_name, last_name, email, department_id
      FROM (
        SELECT s.*, ROW_NUMBER() OVER (PARTITION BY department_id ORDER BY id DESC) AS rn
        FROM students s
        WHERE department_id IN $inClause
      ) t
      WHERE rn <= :lim
      ORDER BY department_id, id DESC
    ";
        $st = $this->db->prepare($sqlStu);
        foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->bindValue(':lim', $limitPerType, PDO::PARAM_INT);
        $st->execute();
        $students = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $dep = (int)$r['department_id'];
            $students[$dep] ??= [];
            $students[$dep][] = $r;
        }

        $sqlIns = "
      SELECT id, first_name, last_name, email, department_id
      FROM (
        SELECT i.*, ROW_NUMBER() OVER (PARTITION BY department_id ORDER BY id DESC) AS rn
        FROM instructors i
        WHERE department_id IN $inClause
      ) t
      WHERE rn <= :lim
      ORDER BY department_id, id DESC
    ";
        $st = $this->db->prepare($sqlIns);
        foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->bindValue(':lim', $limitPerType, PDO::PARAM_INT);
        $st->execute();
        $instructors = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $dep = (int)$r['department_id'];
            $instructors[$dep] ??= [];
            $instructors[$dep][] = $r;
        }

        return [
            'students' => $students,
            'students_count' => $students_count,
            'instructors' => $instructors,
            'instructors_count' => $instructors_count,
        ];
    }


}
