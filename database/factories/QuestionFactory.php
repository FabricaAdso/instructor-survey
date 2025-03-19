<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'question' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['radio', 'text']),
            'options' => $this->faker->randomElement([['option1', 'option2', 'option3'], null]),
            'survey_id' => Survey::factory(),
        ];
    }
}
