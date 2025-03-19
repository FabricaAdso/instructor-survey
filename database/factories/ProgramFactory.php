<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition()
    {
        return [
            'code' => Str::uuid()->toString(), // Usar UUID para garantizar unicidad
            'name' => $this->faker->sentence(3),
        ];
    }
}
