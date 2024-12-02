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
        return view('reports.index', compact('instructors'));
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
    $observations = $answers->whereIn('question_id', [21, 22])->map(fn($answer) => $answer->qualification);

    // Paso 5: Obtener las preguntas asociadas a las respuestas
    $questions = Question::whereIn('id', $reportData->keys())->pluck('question', 'id');

    // Paso 6: Retornar la vista del reporte con toda la información consolidada
    return view('reports.show', [
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
        // Obtener todas las respuestas asociadas al instructor sin filtrar por curso ni programa
        $answers = Answer::where('instructor_id', $instructorId)->get();
        // Agrupar las respuestas por pregunta y calcular promedio y cantidad de respuestas
        $reportData = $answers->groupBy('question_id')->map(function ($group) {
            $calificaciones = $group->pluck('qualification')->map(function ($value) {
                return (int)$value;
            });
            return [
                'average' => $calificaciones->avg(),
                'count' => $group->count(),
            ];
        });

        // Obtener el texto de las preguntas
        $questions = Question::whereIn('id', $reportData->keys())->pluck('question', 'id');

        // Retornar la vista con los datos del reporte
        return view('reports.general', [
            'reportData' => $reportData,
            'questions' => $questions,
            'instructor' => Instructor::find($instructorId)
        ]);
    }
}
