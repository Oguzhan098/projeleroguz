<?php
namespace App\Entities;


class Custodian
{
    public ?int $id = null;
    public string $first_name;
    public string $last_name;
    public ?string $registered_at = null;
    public ?int $student_id = null;


    public function __construct(string $first_name, string $last_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
}