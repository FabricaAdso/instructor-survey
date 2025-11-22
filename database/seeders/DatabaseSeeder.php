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
    }
}


