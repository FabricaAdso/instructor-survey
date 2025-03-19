<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Program;
use App\Models\Municipality;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('C??####'), // Código único
            'is_survey_open' => $this->faker->boolean,
            'program_id' => Program::factory(),
            'municipality_id' => Municipality::factory(),
        ];
    }
}
