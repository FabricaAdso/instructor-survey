<?php

namespace Database\Seeders;

use App\Models\Apprentice;
use App\Models\Course;
use App\Models\Program;
use App\Models\Municipality;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $municipality = Municipality::create([
            'name' => 'AdminMunicipality',
        ]);

        $program = Program::create([
            'code' => 'Admin',
            'name' => 'Admin Program',
        ]);

        $course = Course::create([
            'code' => '123456789',
            'program_id' => $program->id,
            'municipality_id' => $municipality->id,
        ]);

        Apprentice::create([
            'name' => 'Administrador',
            'last_name' => 'User',
            'second_last_name' => 'Admin',
            'identity_document' => '123456789',
            'course_id' => $course->id,
            'role' => 'admin',
        ]);
    }
}
