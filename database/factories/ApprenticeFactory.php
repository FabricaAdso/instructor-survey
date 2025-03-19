<?php

namespace Database\Factories;

use App\Models\Apprentice;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprenticeFactory extends Factory
{
    protected $model = Apprentice::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'state' => $this->faker->randomElement(['En_formacion', 'Etapa_productiva', 'En_comite', 'Desertado', 'Retiro_voluntario', 'Induccion']),
            'course_id' => Course::factory(),
        ];
    }
}
