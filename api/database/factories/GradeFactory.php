<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition()
    {
        return [
            'student_id' => \App\Models\Student::factory(), // Cria um estudante
            'subject_id' => \App\Models\Subject::factory(), // Cria uma disciplina
            'grade' => $this->faker->numberBetween(0, 100),
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 