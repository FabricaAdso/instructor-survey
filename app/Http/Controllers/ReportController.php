<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Program;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;


class ReportController extends Controller
{
    public function admin()
    {
        return view('admin.admin');
    }

    public function index()
    {
        $instructors = Instructor::with(['courses.program'])->get();

        // Agregar las propiedades necesarias
        $instructors->each(function ($instructor) {
            // Verificar si tiene respuestas generales
            $instructor->hasGeneralAnswers = Answer::where('instructor_id', $instructor->id)->exists();

            // Verificar si sus cursos tienen respuestas
            $instructor->courses->each(function ($course) {
                $course->hasAnswers = Answer::where('course_id', $course->id)->exists();
            });
        });


        return view('admin.reports.index', compact('instructors'));
    }
    public function show($courseId, $instructorId, $programId)
    {
        // Paso 1: Verificar que el curso exista y esté relacionado con el instructor
        $course = Course::with('instructors')->find($courseId);

        if (!$course) {
            return back()->withErrors("El curso no existe.");
        }

        // Verificar que el instructor esté asignado al curso
        $instructor = $course->instructors->where('id', $instructorId)->first();
        if (!$instructor) {
            return back()->withErrors("El instructor no está asignado al curso seleccionado.");
        }

        // Paso 2: Obtener las respuestas relacionadas con el curso y el instructor específico
        $answers = Answer::where('instructor_id', $instructorId)
            ->whereHas('course', function ($query) use ($courseId) {
                // Solo incluir respuestas que estén asociadas al curso específico
                $query->where('id', $courseId);
            })
            ->get();

        // Paso 3: Generar el reporte para preguntas menores a 21
        $reportData = $answers->where('question_id', '<', 21)
            ->groupBy('question_id')
            ->map(function ($group) {
                $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                return [
                    'average' => $calificaciones->avg(),
                    'count' => $group->count(),
                ];
            });

        // Paso 4: Recoger observaciones para preguntas abiertas (ID 21 y 22)
        $observations = $answers->whereIn('question_id', [21, 22])
        ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');

       // Paso 5: Obtener las preguntas asociadas a las respuestas
       $questions = Question::whereIn('id', $reportData->keys())
       ->pluck('question', 'id')
       ->values()  // Nos aseguramos de obtener solo los valores
       ->toArray();  // Convertimos a un array simple de JavaScript

        // Paso 6: Retornar la vista del reporte con toda la información consolidada
        return view('admin/reports.show', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),
            'observations' => $observations,
            'instructor' => $instructor,
            'course' => $course,
            'program' => Program::find($programId),
        ]);
    }

    public function reportsDownloadCourse($courseId, $instructorId, $programId)
    {
        try {
        // Paso 1: Verificar que el curso exista y esté relacionado con el instructor
        $course = Course::with('instructors')->find($courseId);

        if (!$course) {
            return back()->withErrors("El curso no existe.");
        }

        // Verificar que el instructor esté asignado al curso
        $instructor = $course->instructors->where('id', $instructorId)->first();
        if (!$instructor) {
            return back()->withErrors("El instructor no está asignado al curso seleccionado.");
        }

        // Paso 2: Obtener las respuestas relacionadas con el curso y el instructor específico
        $answers = Answer::where('instructor_id', $instructorId)
            ->whereHas('course', function ($query) use ($courseId) {
                // Solo incluir respuestas que estén asociadas al curso específico
                $query->where('id', $courseId);
            })
            ->get();

        // Paso 3: Generar el reporte para preguntas menores a 21
        $reportData = $answers->where('question_id', '<', 21)
            ->groupBy('question_id')
            ->map(function ($group) {
                $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                return [
                    'average' => $calificaciones->avg(),
                    'count' => $group->count(),
                ];
            });

        // Paso 4: Recoger observaciones para preguntas abiertas (ID 21 y 22)
        $observations = $answers->whereIn('question_id', [21, 22])
        ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');

        // Paso 5: Preguntas
        $questions = Question::whereIn('id', $reportData->keys())
        ->pluck('question', 'id')
        ->values()
        ->toArray();

        // Paso 6: Retornar la vista del reporte con toda la información consolidada
        $htmlContent = view('admin/reports/courseGrafica', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),
            'observations' => $observations,
            'instructor' => $instructor,
            'course' => $course,
            'program' => Program::find($programId),
            ])->render();

            // Generar el PDF en memoria
            $pdf = Pdf::html($htmlContent)
                ->withBrowserShot(function (Browsershot $browsershot) {
                    $browsershot

                    ->setNodeBinary('/home/linuxbrew/.linuxbrew/bin/node') // Ruta personalizada de Node.js
                    ->setNpmBinary('/home/linuxbrew/.linuxbrew/bin/npm')   // Ruta personalizada de npm
                        ->margins(1, 1, 1, 1, "px")
                        ->waitUntilNetworkIdle();
                });
            // Descargar
            return $pdf->download("reporte-instructor-{$instructorId}-" . now()->format('Y-m-d') . ".pdf");

        } catch (\Exception $e) {
            return back()->withErrors('No se pudo generar el PDF: ' . $e->getMessage());
        }

    }

    public function showGeneral($instructorId)
    {
        // Paso 1: Verificar que el instructor existe
        $instructor = Instructor::find($instructorId);
        if (!$instructor) {
            return back()->withErrors("El instructor no existe.");
        }

        // Paso 2: Obtener todas las respuestas de las fichas asociadas al instructor
        $answers = Answer::where('instructor_id', $instructorId)
            ->whereHas('course', function ($query) {
                $query->whereNotNull('id');
            })
            ->get();

        // Paso 3: Generar el reporte agrupando por pregunta (preguntas menores a 21)
        $reportData = $answers->where('question_id', '<', 21)
            ->groupBy('question_id')
            ->map(function ($group) {
                $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                return [
                    'average' => $calificaciones->avg(),
                    'count' => $group->count(),
                ];
            });

        // Paso 4: Recoger observaciones para preguntas abiertas (ID 21 y 22)
        $observations = $answers->whereIn('question_id', [21, 22])
                ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');
;

        // Paso 5: Obtener las preguntas asociadas a las respuestas
        $questions = Question::whereIn('id', $reportData->keys())
            ->pluck('question', 'id')
            ->values()  // Nos aseguramos de obtener solo los valores
            ->toArray();  // Convertimos a un array simple de JavaScript

        // Paso 6: Retornar la vista del reporte con toda la información consolidada
        return view('admin/reports/general', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),  // Pasamos a JSON
            'observations' => $observations,
            'instructor' => $instructor
        ]);
    }





    public function showGeneralDownload($instructorId)
{
    try {
        // Paso 1: Verificar que el instructor existe
        $instructor = Instructor::find($instructorId);
        if (!$instructor) {
            return back()->withErrors("El instructor no existe.");
        }

        // Paso 2: Obtener todas las respuestas
        $answers = Answer::where('instructor_id', $instructorId)
            ->whereHas('course', function ($query) {
                $query->whereNotNull('id');
            })
            ->get();

        // Paso 3: Generar el reporte
        $reportData = $answers->where('question_id', '<', 21)
            ->groupBy('question_id')
            ->map(function ($group) {
                $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                return [
                    'average' => $calificaciones->avg(),
                    'count' => $group->count(),
                ];
            });

        // Paso 4: Observaciones
        $observations = $answers->whereIn('question_id', [21, 22])
            ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');

        // Paso 5: Preguntas
        $questions = Question::whereIn('id', $reportData->keys())
            ->pluck('question', 'id')
            ->values()
            ->toArray();

        // Generar contenido HTML
        $htmlContent = view('admin/reports.generalGrafica', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),
            'observations' => $observations,
            'instructor' => $instructor
        ])->render();

        // Generar el PDF en memoria
        $pdf = Pdf::html($htmlContent)
            ->withBrowserShot(function (Browsershot $browsershot) {
                $browsershot

                ->setNodeBinary('/home/linuxbrew/.linuxbrew/bin/node') // Ruta personalizada de Node.js
                ->setNpmBinary('/home/linuxbrew/.linuxbrew/bin/npm')   // Ruta personalizada de npm
                    ->margins(1, 1, 1, 1, "px")
                    ->waitUntilNetworkIdle();
            });

        // Descargar el PDF directamente
        return $pdf->download("reporte-instructor-{$instructorId}-" . now()->format('Y-m-d') . ".pdf");

    } catch (\Exception $e) {
        return back()->withErrors('No se pudo generar el PDF: ' . $e->getMessage());
    }
}
}
