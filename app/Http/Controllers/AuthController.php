<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apprentice;
use App\Models\Course;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\VerificationMail;

class AuthController extends Controller {

    public function showLoginForm() {
        return view('auth.login');
    }

    public function loginAdmin(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'username' => 'required', // Campo genérico para name o identity_document
            'password' => 'required',
        ]);

        // Buscar el usuario por name o identity_document
        $user = User::where('name', $request->username)
                    ->orWhere('identity_document', $request->username)
                    ->first();



        // Verificar si el usuario existe, es superusuario y la contraseña es correcta
        if (!$user || !$user->is_superuser || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Credenciales incorrectas o no tienes permisos de administrador.']);
        }

        // Autenticar al usuario
        Auth::login($user);

        // Redirigir al dashboard del administrador
        return redirect()->route('admin.dashboard');
    }

    // public function login(Request $request) {
    //     // Validar los datos del formulario
    //     $request->validate([
    //         'course_code' => 'required|exists:courses,code',
    //         'identity_document' => 'required',
    //     ]);

    //     // Buscar el curso
    //     $course = Course::where('code', $request->course_code)->first();

    //     // Buscar el aprendiz junto con su usuario
    //     $apprentice = Apprentice::whereHas('user', function ($query) use ($request) {
    //         $query->where('identity_document', $request->identity_document);
    //     })
    //     ->where('course_id', $course->id)
    //     ->with('user') // Cargar la relación con user
    //     ->first();

    //     // Si no se encuentra el aprendiz o su usuario, retornar error
    //     if (!$apprentice || !$apprentice->user) {
    //         return back()->withErrors(['error' => 'Datos incorrectos.']);
    //     }

    //     // Obtener el email
    //     $email = $apprentice->user->email;
    //     if (!$email) {
    //         return back()->withErrors(['error' => 'No se encontró un email asociado a este aprendiz.']);
    //     }

    //     // Generar código de verificación
    //     $code = rand(1000, 9999);
    //     VerificationCode::updateOrCreate(
    //         ['apprentice_id' => $apprentice->id],
    //         ['code' => $code, 'expires_at' => Carbon::now()->addMinutes(5)]
    //     );

    //     // Enviar el código por correo
    //     Mail::to($email)->send(new VerificationMail($code));

    //     // Redirigir a la vista de verificación
    //     return redirect()->route('verification.form', ['apprenticeId' => $apprentice->id]);
    // }

    public function login(Request $request) {
        // Validar los datos del formulario
        $request->validate([
            'course_code' => 'required|exists:courses,code',
            'identity_document' => 'required',
        ]);

        // Buscar el curso
        $course = Course::where('code', $request->course_code)->first();

        // Buscar el aprendiz junto con su usuario
        $apprentice = Apprentice::whereHas('user', function ($query) use ($request) {
            $query->where('identity_document', $request->identity_document);
        })
        ->where('course_id', $course->id)
        ->with('user') // Cargar la relación con user
        ->first();

        // Si no se encuentra el aprendiz o su usuario, retornar error
        if (!$apprentice || !$apprentice->user) {
            return back()->withErrors(['error' => 'Datos incorrectos.']);
        }

        // Validar el estado del aprendiz
        if (!in_array($apprentice->state, ['Formacion', 'Etapa_productiva'])) {
            return back()->withErrors(['error' => 'El aprendiz no está habilitado para realizar la encuesta.']);
        }

        // Obtener el email
        $email = $apprentice->user->email;
        if (!$email) {
            return back()->withErrors(['error' => 'No se encontró un email asociado a este aprendiz.']);
        }

        // Generar código de verificación
        $code = rand(1000, 9999);
        VerificationCode::updateOrCreate(
            ['apprentice_id' => $apprentice->id],
            ['code' => $code, 'expires_at' => Carbon::now()->addMinutes(5)]
        );

        // Enviar el código por correo
        Mail::to($email)->send(new VerificationMail($code));

        // Redirigir a la vista de verificación
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

        // Buscar el código de verificación
        $verificationCode = VerificationCode::where('apprentice_id', $request->apprentice_id)
            ->where('code', $request->code)
            ->first();

        // Verificar si el código existe y es válido
        if (!$verificationCode) {
            return back()->withErrors(['error' => 'Código incorrecto.']);
        }

        if (!$verificationCode->isValid()) {
            return back()->withErrors(['error' => 'El código ha expirado. Solicita uno nuevo.']);
        }

        // Eliminar código usado
        $verificationCode->delete();

        // Autenticar usuario
        $apprentice = Apprentice::find($request->apprentice_id);
        Auth::login($apprentice);
        session(['course_id' => $apprentice->course_id]);

        // Establecer sesión para indicar que el código ha sido verificado
        session(['code_verified' => true]);

        return redirect()->route('survey.show', ['apprenticeId' => $apprentice->id, 'surveyId' => 1]);
    }

    public function logout() {
        session()->forget('code_verified');
        Auth::logout();
        return redirect()->route('login');
    }


}
