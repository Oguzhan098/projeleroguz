<?php
declare(strict_types=1);

namespace App\Models;

final class Person extends BaseModel
{
    protected static string $table = 'people';

    public static function findOrCreateByName(string $fullName): int {
        $row = static::first(['full_name' => $fullName]);
        if ($row) return (int)$row['id'];
        return static::create(['full_name' => $fullName]);
    }
}

