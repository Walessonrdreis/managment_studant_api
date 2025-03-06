<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'student_id' => \App\Models\Student::factory(), // Cria um estudante
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['present', 'absent']),
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 