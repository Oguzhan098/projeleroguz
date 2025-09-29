<?php
namespace App\Models;

use App\Core\Model;
use App\Entities\Instructor;

class InstructorModel extends Model
{
    public function all(): array
    { return $this->db->query('SELECT * FROM instructors ORDER BY id DESC')->fetchAll(); }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM instructors WHERE id=:id');
        $st->execute(['id'=>$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(Instructor $i): int
    {
        $st = $this->db->prepare('INSERT INTO instructors(first_name,last_name,email) VALUES(:fn,:ln,:em) RETURNING id');
        $st->execute(['fn'=>$i->first_name,'ln'=>$i->last_name,'em'=>$i->email]);
        return (int)$st->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $st = $this->db->prepare('UPDATE instructors SET first_name=:fn,last_name=:ln,email=:em, updated_at=NOW() WHERE id=:id');
        return $st->execute(['fn'=>$data['first_name'],'ln'=>$data['last_name'],'em'=>$data['email'],'id'=>$id]);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare('DELETE FROM instructors WHERE id=:id');
        return $st->execute(['id'=>$id]);
    }
}
