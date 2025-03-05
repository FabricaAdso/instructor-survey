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

        // Crear usuarios
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
            'name' => 'Maria',
            'last_name' => 'López',
            'email' => 'maria.lopez@example.com',
            'is_superuser' => true,
            'password' => Hash::make('password123'),
        ]);

        // Crear aprendices
        $apprentice1 = Apprentice::create(['user_id' => $user1->id, 'state' => 'Formacion', 'course_id' => $course1->id]);
        $apprentice2 = Apprentice::create(['user_id' => $user2->id, 'state' => 'Etapa_productiva', 'course_id' => $course2->id]);

        // Crear instructores
        $instructor1 = Instructor::create(['user_id' => $user1->id, 'state' => 'Activo', 'is_course_leader' => true]);
        $instructor2 = Instructor::create(['user_id' => $user2->id, 'state' => 'Activo', 'is_course_leader' => false]);

        // Asignar instructores a cursos
        DB::table('course_instructor')->insert([
            ['instructor_id' => $instructor1->id, 'course_id' => $course1->id],
            ['instructor_id' => $instructor2->id, 'course_id' => $course2->id]
        ]);

        // Crear códigos de verificación
        VerificationCode::create([
            'apprentice_id' => $apprentice1->id,
            'code' => '1234',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        VerificationCode::create([
            'apprentice_id' => $apprentice2->id,
            'code' => '5678',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
    }

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
}


