<?php
namespace App\Models;


use App\Core\Model;
use App\Entities\Custodian;
use PDO;


class CustodianModel extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM custodians ORDER BY id DESC')->fetchAll();
    }


    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM custodians WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }


    public function create(Custodian $s): int
    {
        $stmt = $this->db->prepare('INSERT INTO custodians(first_name,last_name,email) VALUES(:fn,:ln,:em) RETURNING id');
        $stmt->execute(['fn'=>$s->first_name,'ln'=>$s->last_name,'em'=>$s->email]);
        return (int)$stmt->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE custodians SET first_name=:fn,last_name=:ln,email=:em, updated_at=NOW() WHERE id=:id');
        return $stmt->execute([
            'fn'=>$data['first_name'], 'ln'=>$data['last_name'], 'em'=>$data['email'], 'id'=>$id
        ]);
    }


    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM custodians WHERE id=:id');
        return $stmt->execute(['id'=>$id]);
    }

    public function searchCount(string $q=''): int
    {
        if ($q === '') {
            return (int)$this->db->query('SELECT COUNT(*) FROM custodians')->fetchColumn();
        }
        $st = $this->db->prepare("SELECT COUNT(*) FROM custodians
        WHERE first_name ILIKE :q OR last_name ILIKE :q OR email ILIKE :q");
        $st->execute(['q' => '%'.$q.'%']);
        return (int)$st->fetchColumn();
    }

    public function searchPaginated(string $q='', int $limit=10, int $offset=0): array
    {
        if ($q === '') {
            $st = $this->db->prepare('SELECT * FROM custodians ORDER BY id DESC LIMIT :lim OFFSET :off');
            $st->bindValue(':lim', $limit, \PDO::PARAM_INT);
            $st->bindValue(':off', $offset, \PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        }
        $st = $this->db->prepare("SELECT * FROM custodians
        WHERE first_name ILIKE :q OR last_name ILIKE :q OR email ILIKE :q
        ORDER BY id DESC LIMIT :lim OFFSET :off");
        $st->bindValue(':q', '%'.$q.'%');
        $st->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $st->bindValue(':off', $offset, \PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }


}
