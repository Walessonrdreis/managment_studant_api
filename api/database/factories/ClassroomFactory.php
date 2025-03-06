<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'capacity' => $this->faker->numberBetween(20, 40),
            'school_id' => \App\Models\School::factory(), // Cria uma escola
        ];
    }
} 