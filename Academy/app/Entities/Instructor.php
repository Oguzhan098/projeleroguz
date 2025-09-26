<?php
namespace App\Entities;


class Instructor
{
    public ?int $id = null;
    public string $first_name;
    public string $last_name;
    public string $email;


    public function __construct(string $first_name, string $last_name, string $email)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
    }
}