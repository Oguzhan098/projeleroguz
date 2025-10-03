<?php
namespace App\Models;

use App\Core\Model;
use App\Entities\Department;
use PDO;

class DepartmentModel extends Model
{

    public function all(): array
    {
        return $this->db->query('SELECT * FROM departments ORDER BY code ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM departments WHERE id = :id');
        $st->execute([':id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(Department $d): int
    {
        $st = $this->db->prepare(
            'INSERT INTO departments(code, name, description) VALUES(:c, :n, :d) RETURNING id'
        );
        $st->execute([':c' => $d->code, ':n' => $d->name, ':d' => $d->description]);
        return (int)$st->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $st = $this->db->prepare(
            'UPDATE departments SET code = :c, name = :n, description = :d, updated_at = NOW() WHERE id = :id'
        );
        return $st->execute([
            ':c' => strtoupper($data['code']),
            ':n' => $data['name'],
            ':d' => $data['description'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare('DELETE FROM departments WHERE id = :id');
        return $st->execute([':id' => $id]);
    }

    public function searchCount(string $q): int
    {
        if ($q === '') {
            return (int)$this->db->query('SELECT COUNT(*) FROM departments')->fetchColumn();
        }
        $st = $this->db->prepare('SELECT COUNT(*) FROM departments WHERE code ILIKE :q OR name ILIKE :q');
        $st->execute([':q' => '%'.$q.'%']);
        return (int)$st->fetchColumn();
    }

    public function searchPaginated(string $q, int $limit, int $offset): array
    {
        if ($q === '') {
            $st = $this->db->prepare('SELECT * FROM departments ORDER BY code ASC LIMIT :lim OFFSET :off');
            $st->bindValue(':lim', $limit, PDO::PARAM_INT);
            $st->bindValue(':off', $offset, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        }

        $st = $this->db->prepare(
            'SELECT * FROM departments
             WHERE code ILIKE :q OR name ILIKE :q
             ORDER BY code ASC LIMIT :lim OFFSET :off'
        );
        $st->bindValue(':q', '%'.$q.'%');
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }


    public function studentsOf(int $depId): array
    {
        $st = $this->db->prepare(
            'SELECT id, first_name, last_name, email
             FROM students WHERE department_id = :d ORDER BY id DESC'
        );
        $st->execute([':d' => $depId]);
        return $st->fetchAll();
    }

    public function instructorsOf(int $depId): array
    {
        $st = $this->db->prepare(
            'SELECT id, first_name, last_name, email
             FROM instructors WHERE department_id = :d ORDER BY id DESC'
        );
        $st->execute([':d' => $depId]);
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


        $params = [];
        $ph = [];
        foreach ($deptIds as $i => $id) {
            $k = ':d'.$i;
            $params[$k] = (int)$id;
            $ph[] = $k;
        }
        $in = '('.implode(',', $ph).')';

        $st = $this->db->prepare(
            "SELECT department_id, COUNT(*) cnt
             FROM students WHERE department_id IN $in GROUP BY department_id"
        );
        foreach ($params as $k => $v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->execute();
        $students_count = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $students_count[(int)$r['department_id']] = (int)$r['cnt'];
        }

        $st = $this->db->prepare(
            "SELECT department_id, COUNT(*) cnt
             FROM instructors WHERE department_id IN $in GROUP BY department_id"
        );
        foreach ($params as $k => $v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->execute();
        $instructors_count = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $instructors_count[(int)$r['department_id']] = (int)$r['cnt'];
        }

        $sqlStu = "
          SELECT id, first_name, last_name, email, department_id
          FROM (
            SELECT s.*,
                   ROW_NUMBER() OVER (PARTITION BY department_id ORDER BY id DESC) rn
            FROM students s WHERE department_id IN $in
          ) t
          WHERE rn <= :lim
          ORDER BY department_id, id DESC";
        $st = $this->db->prepare($sqlStu);
        foreach ($params as $k => $v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->bindValue(':lim', $limitPerType, PDO::PARAM_INT);
        $st->execute();
        $students = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $d = (int)$r['department_id'];
            $students[$d] ??= [];
            $students[$d][] = $r;
        }

        $sqlIns = "
          SELECT id, first_name, last_name, email, department_id
          FROM (
            SELECT i.*,
                   ROW_NUMBER() OVER (PARTITION BY department_id ORDER BY id DESC) rn
            FROM instructors i WHERE department_id IN $in
          ) t
          WHERE rn <= :lim
          ORDER BY department_id, id DESC";
        $st = $this->db->prepare($sqlIns);
        foreach ($params as $k => $v) $st->bindValue($k, $v, PDO::PARAM_INT);
        $st->bindValue(':lim', $limitPerType, PDO::PARAM_INT);
        $st->execute();
        $instructors = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $d = (int)$r['department_id'];
            $instructors[$d] ??= [];
            $instructors[$d][] = $r;
        }

        return compact('students', 'students_count', 'instructors', 'instructors_count');
    }

    public function linkStudent(int $depId, int $studentId): bool
    {
        $st = $this->db->prepare('UPDATE students SET department_id = :d WHERE id = :i');
        return $st->execute([':d' => $depId, ':i' => $studentId]);
    }

    public function unlinkStudent(int $depId, int $studentId): bool
    {
        $st = $this->db->prepare('UPDATE students SET department_id = NULL WHERE id = :i AND department_id = :d');
        return $st->execute([':i' => $studentId, ':d' => $depId]);
    }

    public function linkInstructor(int $depId, int $instructorId): bool
    {
        $st = $this->db->prepare('UPDATE instructors SET department_id = :d WHERE id = :i');
        return $st->execute([':d' => $depId, ':i' => $instructorId]);
    }

    public function unlinkInstructor(int $depId, int $instructorId): bool
    {
        $st = $this->db->prepare('UPDATE instructors SET department_id = NULL WHERE id = :i AND department_id = :d');
        return $st->execute([':i' => $instructorId, ':d' => $depId]);
    }

    public function candidateStudents(int $depId, int $limit = 500): array
    {
        $sql = "SELECT id, first_name, last_name, email, department_id
                FROM students
                WHERE department_id IS NULL OR department_id = :d
                ORDER BY id DESC LIMIT :lim";
        $st = $this->db->prepare($sql);
        $st->bindValue(':d', $depId, PDO::PARAM_INT);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function candidateInstructors(int $depId, int $limit = 500): array
    {
        $sql = "SELECT id, first_name, last_name, email, department_id
                FROM instructors
                WHERE department_id IS NULL OR department_id = :d
                ORDER BY id DESC LIMIT :lim";
        $st = $this->db->prepare($sql);
        $st->bindValue(':d', $depId, PDO::PARAM_INT);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }
}
