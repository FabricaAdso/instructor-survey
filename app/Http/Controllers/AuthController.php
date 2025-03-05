<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apprentice;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\VerificationCode;
use App\Mail\VerificationMail;

class AuthController extends Controller {

    public function showLoginForm() {
        return view('auth.login');
    }

    public function loginAdmin(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $request->username)
                    ->orWhere('identity_document', $request->username)
                    ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Credenciales incorrectas o no tienes permisos de administrador.']);
        }

        if (!$user->is_superuser) {
            return back()->withErrors(['error' => 'Credenciales incorrectas o no tienes permisos de administrador.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Credenciales incorrectas o no tienes permisos de administrador.']);
        }

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function login(Request $request) {
        $request->validate([
            'course_code' => 'required|exists:courses,code',
            'identity_document' => 'required',
        ]);

        $course = Course::where('code', $request->course_code)->first();

        $apprentice = Apprentice::whereHas('user', function ($query) use ($request) {
                $query->where('identity_document', $request->identity_document);
            })
            ->where('course_id', $course->id)
            ->with('user')
            ->first();

        if (!$apprentice || !$apprentice->user) {
            return back()->withErrors(['error' => 'Datos incorrectos.']);
        }

        if (!in_array($apprentice->state, ['Formacion', 'Etapa_productiva'])) {
            return back()->withErrors(['error' => 'El aprendiz no está habilitado para realizar la encuesta.']);
        }

        $email = $apprentice->user->email;
        if (!$email) {
            return back()->withErrors(['error' => 'No se encontró un email asociado a este aprendiz.']);
        }

        $code = rand(1000, 9999);
        VerificationCode::updateOrCreate(
            ['apprentice_id' => $apprentice->id],
            ['code' => $code, 'expires_at' => Carbon::now()->addMinutes(5)]
        );

        Mail::to($email)->send(new VerificationMail($code));

        return redirect()->route('verification.form', ['apprenticeId' => $apprentice->id]);
    }

    public function showVerificationForm($apprenticeId) {
        return view('auth.verify', ['apprenticeId' => $apprenticeId]);
    }

    public function verifyCode(Request $request) {
        $request->validate([
            'apprentice_id' => 'required|exists:apprentices,id',
            'code' => 'required|digits:4',
        ]);

        $verificationCode = VerificationCode::where('apprentice_id', $request->apprentice_id)
            ->where('code', $request->code)
            ->first();

        if (!$verificationCode) {
            return back()->withErrors(['error' => 'Código incorrecto.']);
        }

        if (!$verificationCode->isValid()) {
            return back()->withErrors(['error' => 'El código ha expirado. Solicita uno nuevo.']);
        }

        $verificationCode->delete();

        $apprentice = Apprentice::find($request->apprentice_id);
        Auth::login($apprentice->user);
        session(['course_id' => $apprentice->course_id]);

        session(['code_verified' => true]);

        return redirect()->route('survey.show', ['apprenticeId' => $apprentice->id, 'surveyId' => 1]);
    }

    public function logout() {
        session()->forget('code_verified');
        Auth::logout();
        return redirect()->route('login');
    }


}
