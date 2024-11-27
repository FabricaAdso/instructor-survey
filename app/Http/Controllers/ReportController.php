<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Question;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function courses()
    {
        $courses = Course::with('instructors')->get();
        return view('reports.courses', compact('courses'));
    }
    public function index()
    {
        $questions = Question::included()->get();
        return view('reports.index', compact('questions'));
    }
    public function show($courseId, $instructorId)
    {
        $answers = Answer::whereHas('instructor.courses', function ($query) use ($courseId) {
            $query->where('courses.id', $courseId);
        })->where('instructor_id', $instructorId)->get();
        $reportData = $answers->groupBy('question_id')->map(function ($answers) {
            // return $answers->avg('qualification'); // Calificación promedio
        });
        $questions = $answers->map->question->unique('id')->pluck('text');

        return view('reports.show', [
            'reportData' => $reportData,
            'questions' => $questions,
            'instructor' => Instructor::find($instructorId),
            'course' => Course::find($courseId)
        ]);
    }
}
