<?php
namespace App\Entities;


class Enrollment
{
    public ?int $id = null;
    public int $student_id;
    public int $course_id;
    public ?string $enrolled_at = null; // ISO
    public ?float $grade = null;


    public function __construct(int $student_id, int $course_id)
    {
        $this->student_id = $student_id;
        $this->course_id = $course_id;
    }
}