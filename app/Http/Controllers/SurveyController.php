<?php
namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Apprentice;
use App\Models\Instructor;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function showSurvey($apprenticeId, $surveyId)
    {
        $survey = Survey::with('questions')->find($surveyId);
        $user = Auth::user();

    if (!$user->course) {
        abort(403, 'No estás inscrito en un curso válido.');
    }

    $instructors = $user->course->instructors;

    return view('survey.form', compact('survey', 'instructors'));
    }

    public function storeAnswers(Request $request, $surveyId)
    {
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        foreach ($data['answers'] as $questionId => $answer) {
            $question = Question::find($questionId);
            if (!Auth::check()) {
                return redirect()->route('login.form')->withErrors(['error' => 'Debes iniciar sesión para completar la encuesta.']);
            }
           

            Answer::create([
                'qualification' => $answer,
                'apprentice_id' => null,
                'question_id' => $questionId,
                'instructor_id' => $request->instructor_id,
            ]);
        }

        return redirect()->route('survey.complete')->with('success', 'Tus respuestas han sido guardadas correctamente');
    }

    public function submitSurvey(Request $request, $surveyId)
    {

        foreach ($request->answers as $instructorId => $questions) {
            foreach ($questions as $questionId => $answer) {
                Answer::create([
                    'apprentice_id' => null,
                    'instructor_id' => $instructorId,
                    'question_id' => $questionId,
                    'qualification' => is_array($answer) ? json_encode($answer) : $answer,
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
