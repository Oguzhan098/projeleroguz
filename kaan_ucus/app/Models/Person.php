<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class Person extends Model {
    public function find(int $id): ?array {
        $st = $this->pdo->prepare("SELECT * FROM public.person WHERE id=:id");
        $st->execute([':id'=>$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
