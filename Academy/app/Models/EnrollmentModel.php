<?php
namespace App\Models;


use App\Core\Model;
use App\Entities\Enrollment;


class EnrollmentModel extends Model
{
    public function all(): array
    {
        $sql = 'SELECT e.*, s.first_name AS student_first, s.last_name AS student_last,
c.title AS course_title, c.code AS course_code
FROM enrollments e
JOIN students s ON s.id = e.student_id
JOIN courses c ON c.id = e.course_id
ORDER BY e.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function create(Enrollment $e): int
    {
        $st = $this->db->prepare(
            'INSERT INTO enrollments(student_id,course_id,grade) 
                VALUES(:sid,:cid,:gr) RETURNING id');

        $st->execute(['sid'=>$e->student_id, 'cid'=>$e->course_id, 'gr'=>$e->grade]);
        return (int)$st->fetchColumn();
    }

    public function updateGrade(int $id, ?float $grade): bool
    {
        $st = $this->db->prepare('UPDATE enrollments SET grade = :g WHERE id = :id');
        return $st->execute(['g' => $grade, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare('DELETE FROM enrollments WHERE id=:id');
        return $st->execute(['id'=>$id]);
    }
}