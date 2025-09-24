<?php
declare(strict_types=1);

namespace App\Models;

final class Vehicle extends BaseModel
{
    protected static string $table = 'vehicles';

    public static function findOrCreateByPlate(string $plate): int {
        $row = static::first(['plate' => $plate]);
        if ($row) return (int)$row['id'];
        return static::create(['plate' => $plate]);
    }
}

