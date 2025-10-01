<?php
namespace App\Models;

use App\Core\Model;
use App\Entities\Achievement;
use PDO;

class AchievementModel extends Model
{
    public function all(): array
    {
        $sql = 'SELECT 
       id, 
       first_name, 
       last_name, 
       registered_at, 
       created_at, 
       updated_at
                FROM achievements
                ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = 'SELECT 
     id, 
     first_name, 
     last_name, 
     registered_at, 
     created_at, 
     updated_at
                FROM achievements WHERE id = :id';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(\App\Entities\Achievement $a): int
    {
        $sql = 'INSERT INTO achievements (
                          first_name, 
                          last_name, 
                          registered_at)
            VALUES (:fn, :ln, COALESCE(:ra::timestamptz, NOW()))
            RETURNING id';
        $st = $this->db->prepare($sql);
        $st->execute([
            'fn' => $a->first_name,
            'ln' => $a->last_name,
            'ra' => $a->registered_at, // null olabilir
        ]);
        return (int)$st->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE achievements
                   SET first_name   = :fn,
                       last_name    = :ln,
                       registered_at = :ra
                 WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute([
            'fn' => $data['first_name'],
            'ln' => $data['last_name'],
            'ra' => $data['registered_at'] ?? null, // null geçebilirsin
            'id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM achievements WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function searchCount(string $q = ''): int
    {
        if ($q === '') {
            return (int)$this->db->query('SELECT COUNT(*) FROM achievements')->fetchColumn();
        }
        $sql = 'SELECT COUNT(*) FROM achievements
                WHERE first_name ILIKE :q OR last_name ILIKE :q';
        $st = $this->db->prepare($sql);
        $st->execute(['q' => '%'.$q.'%']);
        return (int)$st->fetchColumn();
    }

    public function searchPaginated(string $q = '', int $limit = 10, int $offset = 0): array
    {
        if ($q === '') {
            $sql = 'SELECT 
    id, 
    first_name, 
    last_name, 
    registered_at, 
    created_at, 
    updated_at
                    FROM achievements
                    ORDER BY id DESC
                    LIMIT :lim OFFSET :off';
            $st = $this->db->prepare($sql);
            $st->bindValue(':lim', $limit, PDO::PARAM_INT);
            $st->bindValue(':off', $offset, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        $sql = '
SELECT 
    a.id, 
    a.first_name, 
    a.last_name, 
    a.registered_at, 
    a.created_at, 
    a.updated_at
FROM achievements a
WHERE a.first_name ILIKE :q1
   OR a.last_name  ILIKE :q2
ORDER BY a.id DESC
LIMIT :lim OFFSET :off';
        $st = $this->db->prepare($sql);

        $like = '%'.$q.'%';
        $st->bindValue(':q1', $like, PDO::PARAM_STR);
        $st->bindValue(':q2', $like, PDO::PARAM_STR);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);

        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
