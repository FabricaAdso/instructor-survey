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


class LeaderReportController extends Controller
{

    public function leaderindex(Request $request)
    {
        $isSurveyOpen = Course::where('is_survey_open', true)->exists();

        $query = Instructor::with([
            'user',
            'answers',
            'coursesSurveyOpen' => function ($query) {
                $query->with('program');
            },
            'courses',
            'knowledgeNetwork'
        ]);

        if ($request->filled('instructor_search')) {
            $search = $request->input('instructor_search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('identity_document', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $user = $request->user();

        // Si el usuario es líder, filtrar los instructores por el mismo knowledge_network
        if ($user->is_area_leader) {
            $leader = \App\Models\AreaLeader::where('user_id', $user->id)->first();
            if ($leader) {
                $query->where('knowledge_network_id', $leader->knowledge_network_id);
            } else {
                abort(403, 'No se encontró área de liderazgo para este usuario.');
            }
            $knowledgeNetwork = $user->areaLeader->knowledgeNetwork;
            $networkName = $knowledgeNetwork->name;

        }

        $instructors = $query->paginate(10);
        // dd($instructors)
        return view('leader.reports.index', compact('instructors', 'isSurveyOpen', 'user'));
    }


    public function toggleSurveyStatus(Request $request)
    {

    }

    public function leaderinstructorsTable(Request $request)
    {
        $query = Instructor::with('user', 'answers', 'courses');

        if ($request->filled('instructor_search')) {
            $search = $request->input('instructor_search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('identity_document', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $user = $request->user();

        // Si el usuario es líder, filtrar los instructores por el mismo knowledge_network
        if ($user->is_area_leader) {
            $leader = \App\Models\AreaLeader::where('user_id', $user->id)->first();
            if ($leader) {
                $query->where('knowledge_network_id', $leader->knowledge_network_id);
            } else {
                abort(403, 'No se encontró área de liderazgo para este usuario.');
            }
        }

        $instructors = $query->paginate(10);

        return view('leader.menu.tableIndex', compact('instructors'));
    }

    public function leadershow($courseId, $instructorId)
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

        return view('leader/reports.show', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),
            'observations' => $observations,
            'instructor' => $instructor,
            'course' => $course,
        ]);
    }

    public function leadershowGeneral($instructorId)
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

        return view('leader/reports/general', [
            'reportData' => $reportData,
            'questions' => json_encode($questions),
            'observations' => $observations,
            'instructor' => $instructor
        ]);
    }

}
