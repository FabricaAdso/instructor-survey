<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Course;
use App\Models\Apprentice;
use App\Models\Instructor;
use App\Models\KnowledgeNetwork;
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

        // // Crear programas
        // $program1 = Program::create(['code' => 'P001', 'name' => 'Desarrollo de Software']);
        // $program2 = Program::create(['code' => 'P002', 'name' => 'Administración de Empresas']);

        // // Crear cursos
        // $course1 = Course::create(['code' => '001', 'program_id' => $program1->id, 'municipality_id' => 1]);
        // $course2 = Course::create(['code' => '002', 'program_id' => $program2->id, 'municipality_id' => 1]);

        // // Crear Red de Conocimiento
        // $knowledgeNetwork1 = KnowledgeNetwork::create(['name' => 'ADSO']);

        // $user1 = User::create([
        //     'identity_document' => '1002958845',
        //     'name' => 'Antonio',
        //     'last_name' => 'Rodriguez',
        //     'email' => 'anttoniio2312@gmail.com',
        //     'is_superuser' => false,
        //     'password' => Hash::make('password123'),
        // ]);

        // $user2 = User::create([
        //     'identity_document' => '1000001654',
        //     'name' => 'Diego',
        //     'last_name' => 'Armando',
        //     'email' => 'DiegoArmando@gmail.com',
        //     'is_superuser' => false,
        //     'password' => Hash::make('password123'),
        // ]);

        // $user3 = User::create([
        //     'identity_document' => '1000023584654',
        //     'name' => 'Adriano',
        //     'last_name' => 'Ribeiro',
        //     'email' => 'AdrianoRibeiro@gmail.com',
        //     'is_superuser' => false,
        //     'password' => Hash::make('password123'),
        // ]);

        // $user4 = User::create([
        //     'identity_document' => '100054',
        //     'name' => 'Edson',
        //     'last_name' => 'Arantes',
        //     'email' => 'EdsonArantes@gmail.com',
        //     'is_superuser' => false,
        //     'password' => Hash::make('password123'),
        // ]);

        // // Crear aprendices
        // $apprentice1 = Apprentice::create(['user_id' => $user1->id, 'state' => 'En_formacion', 'course_id' => $course1->id]);

        // // Crear instructores
        // $instructor1 = Instructor::create(['user_id' => $user2->id, 'state' => 'Activo', 'knowledge_network_id' => $knowledgeNetwork1->id]);
        // $instructor2 = Instructor::create(['user_id' => $user3->id, 'state' => 'Activo', 'knowledge_network_id' => $knowledgeNetwork1->id]);
        // $instructor3 = Instructor::create(['user_id' => $user4->id, 'state' => 'Activo', 'knowledge_network_id' => $knowledgeNetwork1->id]);


        // // Asignar instructores a cursos
        // DB::table('course_instructor')->insert([
        //     ['instructor_id' => $instructor1->id, 'course_id' => $course1->id],
        //     ['instructor_id' => $instructor2->id, 'course_id' => $course1->id],
        //     ['instructor_id' => $instructor3->id, 'course_id' => $course1->id],
        // ]);

    }
}


