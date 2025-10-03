<?php
namespace App\Entities;


class Student
{
    public ?int $id = null;
    public string $first_name;
    public string $last_name;
    public string $email;
    public ?string $registered_at = null;


    public function __construct(string $first_name, string $last_name, string $email)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
    }
}