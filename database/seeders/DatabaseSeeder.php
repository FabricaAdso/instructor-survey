<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Course;
use App\Models\Apprentice;
use App\Models\Instructor;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([MunicipalitySeeder::class, SurveySeeder::class, AdminUserSeeder::class]);

        // Crear programas
        $program1 = Program::create(['code' => 'P001', 'name' => 'Desarrollo de Software']);
        $program2 = Program::create(['code' => 'P002', 'name' => 'Administración de Empresas']);

        // Crear cursos
        $course1 = Course::create(['code' => '001', 'program_id' => $program1->id, 'municipality_id' => 1]);
        $course2 = Course::create(['code' => '002', 'program_id' => $program2->id, 'municipality_id' => 1]);

        $user1 = User::create([
            'identity_document' => '1002958845',
            'name' => 'Antonio',
            'last_name' => 'Rodriguez',
            'email' => 'anttoniio2312@gmail.com',
            'is_superuser' => false,
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'identity_document' => '1002958846',
            'name' => 'Camilo',
            'last_name' => 'Maca',
            'email' => 'camilo@gmail.com',
            'is_superuser' => false,
            'password' => Hash::make('password123'),
        ]);

        $user3 = User::create([
            'identity_document' => '1002958847',
            'name' => 'Alexander',
            'last_name' => 'Pardo',
            'email' => 'jhon@gmail.com',
            'is_superuser' => false,
            'password' => Hash::make('password123'),
        ]);

        // Crear aprendices
        $apprentice1 = Apprentice::create(['user_id' => $user1->id, 'state' => 'Formacion', 'course_id' => $course1->id]);
        $apprentice2 = Apprentice::create(['user_id' => $user2->id, 'state' => 'En_comite', 'course_id' => $course1->id]);

        // Crear instructores
        $instructor1 = Instructor::create(['user_id' => $user3->id, 'state' => 'Activo', 'is_course_leader' => true]);

        // Asignar instructores a cursos
        DB::table('course_instructor')->insert([
            ['instructor_id' => $instructor1->id, 'course_id' => $course1->id],
        ]);

    }
}


