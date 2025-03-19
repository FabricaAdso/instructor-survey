<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\User;
use App\Models\KnowledgeNetwork;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorFactory extends Factory
{
    protected $model = Instructor::class;

    public function definition()
    {
        return [
            'state' => $this->faker->randomElement(['Activo', 'Inactivo']),
            'is_course_leader' => $this->faker->boolean,
            'user_id' => User::factory(),
            'knowledge_network_id' => KnowledgeNetwork::factory(),
        ];
    }
}
