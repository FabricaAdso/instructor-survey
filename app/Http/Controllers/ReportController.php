<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Program;
use App\Models\Question;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\Survey;
use Illuminate\Support\Facades\Log;

use function Spatie\LaravelPdf\Support\pdf;


class ReportController extends Controller
{


    public function index()
    {
        $isSurveyOpen = Course::where('is_survey_open', true)->exists();
        $instructors = Instructor::with([
            'user',
            'courses' => function($query) {
                $query->where('is_survey_open', true)
                      ->with('program');
            },
        ])->paginate(10);

        return view('admin.reports.index', compact('instructors', 'isSurveyOpen'));
    }




    public function toggleSurveyStatus(Request $request)
    {
        try {
            // Cambiar el estado de la encuesta para todos los cursos
            $newStatus = !Course::where('is_survey_open', true)->exists();
            Course::query()->update(['is_survey_open' => $newStatus]);

            // Si se está cerrando la encuesta, consolidar los datos
            if (!$newStatus) {
                $closureDate = Carbon::now()->format('Y-m-d');
                Artisan::call('survey:consolidate', ['closure_date' => $closureDate]);
            }

            return response()->json([
                'message' => 'Estado de la encuesta actualizado',
                'is_survey_open' => $newStatus
            ]);
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al cambiar el estado de la encuesta: ' . $e->getMessage());

            // Devolver una respuesta de error en formato JSON
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function instructorsTable()
{
    $instructors = Instructor::with([
        'user',
        'courses' => function($query) {
            $query->where('is_survey_open', true)
                  ->with('program');
        },
    ])->paginate(10);

    return view('admin.menu.tableIndex', compact('instructors'));
}


    public function show($courseId, $instructorId, $programId)
    {
        $course = Course::with('instructors')->find($courseId);

        if (!$course) {
            return back()->withErrors("El curso no existe.");
        }

        $instructor = $course->instructors->where('id', $instructorId)->first();
        if (!$instructor) {
            return back()->withErrors("El instructor no está asignado al curso seleccionado.");
        }

        $answers = Answer::where('instructor_id', $instructorId)
            ->whereHas('course', function ($query) use ($courseId) {
                $query->where('id', $courseId);
            })
            ->get();

        $reportData = $answers->where('question_id', '<', 21)
            ->groupBy('question_id')
            ->map(function ($group) {
                $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                return [
                    'average' => $calificaciones->avg(),
                    'count' => $group->count(),
                ];
            });

        $observations = $answers->whereIn('question_id', [21, 22])
            ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');

        $questions = Question::whereIn('id', $reportData->keys())
            ->pluck('question', 'id')
            ->values()
            ->toArray();

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
            $course = Course::with('instructors')->find($courseId);

            if (!$course) {
                return back()->withErrors("El curso no existe.");
            }

            $instructor = $course->instructors->where('id', $instructorId)->first();
            if (!$instructor) {
                return back()->withErrors("El instructor no está asignado al curso seleccionado.");
            }

            $answers = Answer::where('instructor_id', $instructorId)
                ->whereHas('course', function ($query) use ($courseId) {
                    $query->where('id', $courseId);
                })
                ->get();

            $reportData = $answers->where('question_id', '<', 21)
                ->groupBy('question_id')
                ->map(function ($group) {
                    $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                    return [
                        'average' => $calificaciones->avg(),
                        'count' => $group->count(),
                    ];
                });

            $observations = $answers->whereIn('question_id', [21, 22])
                ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');

            $questions = Question::whereIn('id', $reportData->keys())
                ->pluck('question', 'id')
                ->values()
                ->toArray();

            $htmlContent = view('admin/reports/courseGrafica', [
                'reportData' => $reportData,
                'questions' => json_encode($questions),
                'observations' => $observations,
                'instructor' => $instructor,
                'course' => $course,
                'program' => Program::find($programId),
            ])->render();

            $pdf = Pdf::html($htmlContent)
                ->withBrowserShot(function (Browsershot $browsershot) {
                    $browsershot

                        ->setNodeBinary('/home/linuxbrew/.linuxbrew/bin/node')
                        ->setNpmBinary('/home/linuxbrew/.linuxbrew/bin/npm')
                        ->margins(1, 1, 1, 1, "px")
                        ->waitUntilNetworkIdle();
                });
            return $pdf->download("reporte-instructor-{$instructorId}-" . now()->format('Y-m-d') . ".pdf");
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function showGeneral($instructorId)
    {
        $instructor = Instructor::find($instructorId);
        if (!$instructor) {
            return back()->withErrors("El instructor no existe.");
        }

        $answers = Answer::where('instructor_id', $instructorId)
            ->whereHas('course', function ($query) {
                $query->whereNotNull('id');
            })
            ->get();

        $reportData = $answers->where('question_id', '<', 21)
            ->groupBy('question_id')
            ->map(function ($group) {
                $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                return [
                    'average' => $calificaciones->avg(),
                    'count' => $group->count(),
                ];
            });

        $observations = $answers->whereIn('question_id', [21, 22])
            ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');;

        $questions = Question::whereIn('id', $reportData->keys())
            ->pluck('question', 'id')
            ->values()
            ->toArray();

        return view('admin/reports/general', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),
            'observations' => $observations,
            'instructor' => $instructor
        ]);
    }

    public function showGeneralDownload($instructorId)
    {
        try {
            $instructor = Instructor::find($instructorId);
            if (!$instructor) {
                return back()->withErrors("El instructor no existe.");
            }

            $answers = Answer::where('instructor_id', $instructorId)
                ->whereHas('course', function ($query) {
                    $query->whereNotNull('id');
                })
                ->get();

            $reportData = $answers->where('question_id', '<', 21)
                ->groupBy('question_id')
                ->map(function ($group) {
                    $calificaciones = $group->pluck('qualification')->map(fn($value) => (int)$value);
                    return [
                        'average' => $calificaciones->avg(),
                        'count' => $group->count(),
                    ];
                });

            $observations = $answers->whereIn('question_id', [21, 22])
                ->filter(fn($answer) => !is_null($answer->qualification) && $answer->qualification !== '');

            $questions = Question::whereIn('id', $reportData->keys())
                ->pluck('question', 'id')
                ->values()
                ->toArray();

            $htmlContent = view('admin/reports.generalGrafica', [
                'reportData' => $reportData,
                'questions' => json_encode($questions),
                'observations' => $observations,
                'instructor' => $instructor
            ])->render();

            $pdf = Pdf::html($htmlContent)
                ->withBrowserShot(function (Browsershot $browsershot) {
                    $browsershot

                        ->setNodeBinary('/home/linuxbrew/.linuxbrew/bin/node')
                        ->setNpmBinary('/home/linuxbrew/.linuxbrew/bin/npm')
                        ->margins(1, 1, 1, 1, "px")
                        ->waitUntilNetworkIdle();
                });

            return $pdf->download("reporte-instructor-{$instructorId}-" . now()->format('Y-m-d') . ".pdf");
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo generar el PDF: ' . $e->getMessage());
        }
    }
}
