<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Program;
use App\Models\Question;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function admin()
    {
        return view('admin.admin');
    }

    public function index()
    {
        $instructors = Instructor::with(['courses.program'])->get();
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
        $observations = $answers->whereIn('question_id', [21, 22]);

        // Paso 5: Obtener las preguntas asociadas a las respuestas
        $questions = Question::whereIn('id', $reportData->keys())->pluck('question', 'id');

        // Paso 6: Retornar la vista del reporte con toda la información consolidada
        return view('admin/reports.show', [
            'reportData' => $reportData,
            'questions' => $questions,
            'observations' => $observations,
            'instructor' => $instructor,
            'course' => $course,
            'program' => Program::find($programId),
        ]);
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
            // Considera solo las respuestas asociadas a cursos activos o asignados al instructor
            $query->whereNotNull('id'); // Puedes añadir condiciones específicas si lo deseas
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
    $observations = $answers->whereIn('question_id', [21, 22]);

    // Paso 5: Obtener las preguntas asociadas a las respuestas
    $questions = Question::whereIn('id', $reportData->keys())->pluck('question', 'id');

    // Paso 6: Retornar la vista del reporte con toda la información consolidada
    return view('admin/reports.general', [
        'reportData' => $reportData,
        'questions' => $questions,
        'observations' => $observations,
        'instructor' => $instructor
    ]);
}
}
