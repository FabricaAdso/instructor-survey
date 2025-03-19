<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Apprentice;
use App\Models\Instructor;
use App\Models\Question;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition()
    {
        return [
            'type' => 'default_value',
            'qualification' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'apprentice_id' => Apprentice::factory(),
            'instructor_id' => Instructor::factory(),
            'question_id' => Question::factory(),
            'course_id' => Course::factory(),
        ];
    }
}
