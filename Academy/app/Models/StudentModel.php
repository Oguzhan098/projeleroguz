<?php
namespace App\Models;


use App\Core\Model;
use App\Entities\Student;
use PDO;


class StudentModel extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM students ORDER BY id DESC')->fetchAll();
    }


    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }


    public function create(Student $s): int
    {
        $stmt = $this->db->prepare('INSERT INTO students(first_name,last_name,email) VALUES(:fn,:ln,:em) RETURNING id');
        $stmt->execute(['fn'=>$s->first_name,'ln'=>$s->last_name,'em'=>$s->email]);
        return (int)$stmt->fetchColumn();
    }


    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE students SET first_name=:fn,last_name=:ln,email=:em, updated_at=NOW() WHERE id=:id');
        return $stmt->execute([
            'fn'=>$data['first_name'], 'ln'=>$data['last_name'], 'em'=>$data['email'], 'id'=>$id
        ]);
    }


    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM students WHERE id=:id');
        return $stmt->execute(['id'=>$id]);
    }
}