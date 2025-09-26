<?php
namespace App\Models;


use App\Core\Model;
use App\Entities\Course;
use PDO;


class CourseModel extends Model
{
    public function all(): array
    {
        $sql = 'SELECT c.*, i.first_name AS instructor_first, i.last_name AS instructor_last
FROM courses c
LEFT JOIN instructors i ON i.id = c.instructor_id
ORDER BY c.id DESC';
        return $this->db->query($sql)->fetchAll();
    }


    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id=:id');
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }


    public function create(Course $c): int
    {
        $stmt = $this->db->prepare('INSERT INTO courses(code,title,description,instructor_id) VALUES(:code,:title,:desc,:iid) RETURNING id');
        $stmt->execute([
            'code'=>$c->code,'title'=>$c->title,'desc'=>$c->description,'iid'=>$c->instructor_id
        ]);
        return (int)$stmt->fetchColumn();
    }


    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE courses SET code=:code,title=:title,description=:desc,instructor_id=:iid, updated_at=NOW() WHERE id=:id');
        return $stmt->execute([
            'code'=>$data['code'],'title'=>$data['title'],'desc'=>$data['description']??null,'iid'=>$data['instructor_id']??null,'id'=>$id
        ]);
    }


    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id=:id');
        return $stmt->execute(['id'=>$id]);
    }
}