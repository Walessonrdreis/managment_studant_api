<?php

namespace Database\Factories;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition()
    {
        return [
            'student_id' => \App\Models\Student::factory(), // Cria um estudante
            'subject_id' => \App\Models\Subject::factory(), // Cria uma disciplina
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 