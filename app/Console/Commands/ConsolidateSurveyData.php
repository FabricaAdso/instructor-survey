<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Answer;
use App\Models\SurveySummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ConsolidateSurveyData extends Command
{
    protected $signature = 'survey:consolidate {closure_date}';
    protected $description = 'Consolidate survey data into summary table';

    public function handle()
    {
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
                // Calcular el promedio y el total de respuestas para esta pregunta e instructor
                $answers = Answer::where('instructor_id', $instructor->instructor_id)
                    ->where('question_id', $question->question_id)
                    ->get();

                $totalResponses = $answers->count();
                $averageQualification = $answers->avg(function($answer) {
                    return (float) $answer->qualification;
                });
                // Guardar los datos consolidados en la tabla survey_summaries
                SurveySummary::create([
                    'instructor_id' => $instructor->instructor_id,
                    'question_id' => $question->question_id,
                    'average_qualification' => $averageQualification,
                    'total_responses' => $totalResponses,
                    'survey_identifier' => $closureDate, // Fecha de cierre de la encuesta
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
