<?php

namespace Database\Factories;

use App\Models\StudentSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentSubjectFactory extends Factory
{
    protected $model = StudentSubject::class;

    public function definition()
    {
        return [
            'student_id' => \App\Models\Student::factory(), // Cria um estudante
            'subject_id' => \App\Models\Subject::factory(), // Cria uma disciplina
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 