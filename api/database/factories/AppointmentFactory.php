<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'student_id' => \App\Models\Student::factory(), // Cria um estudante
            'teacher_id' => \App\Models\Teacher::factory(), // Cria um professor
            'date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 