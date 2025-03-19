<?php

namespace Database\Factories;

use App\Models\OpenQuestion;
use App\Models\Instructor;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpenQuestionFactory extends Factory
{
    protected $model = OpenQuestion::class;

    public function definition()
    {
        return [
            'survey_identifier' => $this->faker->uuid,
            'response' => $this->faker->paragraph,
            'instructor_id' => Instructor::factory(),
            'question_id' => Question::factory(),
        ];
    }
}
