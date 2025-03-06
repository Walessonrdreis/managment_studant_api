<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'subject' => $this->faker->word,
            'user_id' => \App\Models\User::factory(), // Cria um usuário
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 