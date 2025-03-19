<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Answer;
use App\Models\SurveySummary;
use App\Models\OpenQuestion;
use Illuminate\Support\Facades\DB;

class ConsolidateSurveyData extends Command
{
    protected $signature = 'survey:consolidate {closure_date}';
    protected $description = 'Consolidate survey data into summary table and handle open questions';

    public function handle()
    {
        $closureDate = $this->argument('closure_date');

        $closureDate = $this->argument('closure_date');

        // Obtener todos los instructores únicos que tienen respuestas
        $instructors = Answer::select('instructor_id')->distinct()->get();

        foreach ($instructors as $instructor) {
            // Obtener todas las preguntas únicas para este instructor
            $questions = Answer::where('instructor_id', $instructor->instructor_id)
                ->select('question_id')
                ->distinct()
                ->get();

            foreach ($questions as $question) {
                // Verificar si la pregunta es de respuesta abierta (preguntas 21 y 22)
                if ($question->question_id == 21 || $question->question_id == 22) {
                    // Obtener las respuestas abiertas para esta pregunta e instructor
                    $openAnswers = Answer::where('instructor_id', $instructor->instructor_id)
                        ->where('question_id', $question->question_id)
                        ->get();

                    // Guardar cada respuesta abierta en la tabla open_questions
                    foreach ($openAnswers as $answer) {
                        OpenQuestion::create([
                            'survey_identifier' => $closureDate,
                            'instructor_id' => $instructor->instructor_id,
                            'question_id' => $question->question_id,
                            'response' => $answer->qualification, // Asumiendo que 'qualification' contiene la respuesta abierta
                        ]);
                    }
                } else {
                    // Calcular el promedio y el total de respuestas para preguntas numéricas (1 a 20)
                    $answers = Answer::where('instructor_id', $instructor->instructor_id)
                        ->where('question_id', $question->question_id)
                        ->get();

                    $totalResponses = $answers->count();
                    $averageQualification = $answers->avg('qualification');

                    // Guardar los datos consolidados en la tabla survey_summaries
                    SurveySummary::create([
                        'instructor_id' => $instructor->instructor_id,
                        'question_id' => $question->question_id,
                        'average_qualification' => $averageQualification,
                        'total_responses' => $totalResponses,
                        'survey_identifier' => $closureDate,
                    ]);
                }
            }
        }

        // Opcional: Eliminar los datos de la tabla answers y course_instructor después de la consolidación
        Answer::truncate();
        DB::table('course_instructor')->truncate();


        $this->info('Survey data consolidated successfully.');
    }
}

// ejecutar manualmente:
// php artisan survey:consolidate    ->   routes/console.php
