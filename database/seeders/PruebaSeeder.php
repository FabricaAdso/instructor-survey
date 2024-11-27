<?php
// namespace Database\Seeders;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;
// use Faker\Factory as Faker;;

// class PruebaSeeder extends Seeder
//     {
//         public function run()
//     {
//         $faker = Faker::create();


//         $programs = [];
//         for ($i = 0; $i <= 20; $i++) {
//             $programs[] = DB::table('programs')->insertGetId([
//                 'code' => strtoupper($faker->bothify('PROG-###')),
//                 'name' => $faker->sentence(3),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }


//         $municipalities = [];
//         for ($i = 0; $i <=5; $i++) {
//             $municipalities[] = DB::table('municipalities')->insertGetId([
//                 'name' => $faker->city,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }


//         $courses = [];
//         for ($i = 0; $i <=10; $i++) {
//             $courses[] = DB::table('courses')->insertGetId([
//                 'code' => strtoupper($faker->bothify('COURSE-###')),
//                 'program_id' => $faker->randomElement($programs),
//                 'municipality_id' => $faker->randomElement($municipalities),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }

//         $users = [];
//         for ($i = 0; $i <=40; $i++) {
//             $users[] = DB::table('users')->insertGetId([
//                 'identity_document' => $faker->unique()->numerify('########'),
//                 'first_name' => $faker->firstName,
//                 'middle_name' => $faker->optional()->firstName,
//                 'last_name' => $faker->lastName,
//                 'second_last_name' => $faker->optional()->lastName,
//                 'email' => $faker->unique()->safeEmail,
//                 'email_verified_at' => $faker->optional()->dateTime,
//                 'remember_token' => Str::random(10),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }


//         $apprentices = [];
//         foreach (array_slice($users, 0, 30) as $userId) {
//             $apprentices[] = DB::table('apprentices')->insertGetId([
//                 'user_id' => $userId,
//                 'course_id' => $faker->randomElement($courses),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }

//         $instructors = [];
//         foreach (array_slice($users, 10, 10) as $userId) {
//             $instructors[] = DB::table('instructors')->insertGetId([
//                 'user_id' => $userId,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }


//         $surveys = [];
//         for ($i = 0; $i <=5; $i++) {
//             $surveys[] = DB::table('surveys')->insertGetId([
//                 'name' => $faker->sentence(3),
//                 'description' => $faker->paragraph,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }


//         $questions = [];
//         foreach ($surveys as $surveyId) {
//             for ($i = 0; $i <=5; $i++) {
//                 $questions[] = DB::table('questions')->insertGetId([
//                     'type' => $faker->randomElement(['text', 'choice', 'rating']),
//                     'question' => $faker->sentence,
//                     'survey_id' => $surveyId,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);
//             }
//         }

//         foreach ($apprentices as $apprenticeId) {
//             foreach ($questions as $questionId) {
//                 DB::table('answers')->insert([
//                     'type' => $faker->randomElement(['text', 'choice', 'rating']),
//                     'qualification' => $faker->randomElement(['Good', 'Average', 'Bad']),
//                     'apprentice_id' => $apprenticeId,
//                     'instructor_id' => $faker->randomElement($instructors),
//                     'question_id' => $questionId,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);
//             }
//         }


//         foreach ($instructors as $instructorId) {
//             DB::table('course_instructor')->insert([
//                 'instructor_id' => $instructorId,
//                 'course_id' => $faker->randomElement($courses),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }
//     }
//     }
