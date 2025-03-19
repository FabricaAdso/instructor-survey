<?php

namespace Database\Factories;

use App\Models\CourseInstructor;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseInstructorFactory extends Factory
{
    // protected $model = CourseInstructor::class;

    public function definition()
    {
        return [
            'instructor_id' => Instructor::factory(),
            'course_id' => Course::factory(),
        ];
    }
}
