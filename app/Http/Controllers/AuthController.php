<?php

namespace App\Http\Controllers;

use App\Models\Apprentice;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'course_code' => 'required|exists:courses,code',
            'identity_document' => 'required|exists:apprentices,identity_document',
        ]);

        $course = Course::where('code', $request->course_code)->first();

        if ($course) {
            $apprentice = Apprentice::where('course_id', $course->id)
                ->where('identity_document', $request->identity_document)
                ->first();



            if ($apprentice) {
                Auth::login($apprentice);
                session(['course_id' => $course->id]);

                if ($apprentice->role === 'admin') {
                    return redirect()->route('reports.index');
                }

                return redirect()->route('survey.show', ['apprenticeId' => $apprentice->id, 'surveyId' => 1]);
            }
        }

        return back()->withErrors(['error' => 'Curso o número de identificación incorrectos.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }



}
