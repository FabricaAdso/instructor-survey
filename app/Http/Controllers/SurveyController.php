<?php
namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function showSurvey($apprenticeId, $surveyId)
{
    $survey = Survey::with('questions')->find($surveyId);
    $user = Auth::user();

    if (!$user->apprentice || !$user->apprentice->course) {
        abort(403, 'No estás inscrito en un curso válido.');
    }

    $instructors = $user->apprentice->course->instructors()->with('user')->get();

    $questionsCopy = $survey->questions->toBase();
    $closedQuestions = $questionsCopy->take(20);
    $openQuestions = $questionsCopy->slice(20);

    $pageSizes = [6, 4, 6, 4];
    $pages = [];
    foreach ($pageSizes as $size) {
        $pages[] = $closedQuestions->splice(0, $size);
    }

    return view('survey.form', compact('survey', 'instructors', 'pages', 'openQuestions', 'user'));
}


    public function submitSurvey(Request $request, $surveyId)
    {
        $user = Auth::user();

        if (!$user->apprentice || !$user->apprentice->course) {
            return redirect()->route('survey.form', ['apprenticeId' => $user->apprentice->id, 'surveyId' => $surveyId])
                            ->withErrors(['error' => 'No estás inscrito en un curso válido.']);
        }

        $course = $user->apprentice->course;

        foreach ($request->answers as $instructorId => $questions) {
            foreach ($questions as $questionId => $answer) {
                Answer::create([
                    'apprentice_id' => $user->apprentice->id,
                    'instructor_id' => $instructorId,
                    'question_id' => $questionId,
                    'qualification' => is_array($answer) ? json_encode($answer) : $answer,
                    'course_id' => $course->id,
                ]);
            }
        }

        return redirect()->route('survey.complete');
    }

    public function complete()
    {
        return view('survey.complete');
    }
}
