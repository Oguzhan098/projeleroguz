<?php
namespace App\Entities;

class Department
{
    public ?int $id = null;
    public string $code;
    public string $name;
    public ?string $description = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct(string $code, string $name, ?string $description = null)
    {
        $this->code = strtoupper($code);
        $this->name = $name;
        $this->description = $description;
    }

    public static function isValidCode(string $code): bool
    {
        return (bool)preg_match('/^[A-Z0-9_\-]{2,20}$/', $code);
    }
}
