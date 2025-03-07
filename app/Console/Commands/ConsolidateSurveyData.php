<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Answer;
use App\Models\SurveySummary;
use Carbon\Carbon;

class ConsolidateSurveyData extends Command
{
    protected $signature = 'survey:consolidate';
    protected $description = 'Consolidate survey data into summary table';

    public function handle()
    {
        // Obtener todos los instructores únicos que tienen respuestas
        $instructors = Answer::select('instructor_id')->distinct()->get();

        foreach ($instructors as $instructor) {
            // Obtener todas las preguntas únicas para este instructor
            $questions = Answer::where('instructor_id', $instructor->instructor_id)
                ->select('question_id')
                ->distinct()
                ->get();

            foreach ($questions as $question) {
                // Calcular el promedio y el total de respuestas para esta pregunta e instructor
                $answers = Answer::where('instructor_id', $instructor->instructor_id)
                    ->where('question_id', $question->question_id)
                    ->get();

                $totalResponses = $answers->count();
                $averageQualification = $answers->avg('qualification');

                // Guardar los datos consolidados en la tabla survey_summaries
                SurveySummary::create([
                    'instructor_id' => $instructor->instructor_id,
                    'question_id' => $question->question_id,
                    'course_id' => $answers->first()->course_id, // Asumimos que todas las respuestas son del mismo curso
                    'average_qualification' => $averageQualification,
                    'total_responses' => $totalResponses,
                    'survey_identifier' => 'Encuesta ' . Carbon::now()->format('Y-m-d'), // Identificador único
                ]);
            }
        }

        // Opcional: Eliminar los datos de la tabla answers después de la consolidación
        Answer::truncate();

        $this->info('Survey data consolidated successfully.');
    }
}

// ejecutar manualmente:
// php artisan survey:consolidate    ->   routes/console.php
