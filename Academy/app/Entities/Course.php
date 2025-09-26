<?php
namespace App\Entities;


class Course
{
    public ?int $id = null;
    public string $code;
    public string $title;
    public ?string $description = null;
    public ?int $instructor_id = null;


    public function __construct(string $code, string $title)
    {
        $this->code = $code;
        $this->title = $title;
    }
}