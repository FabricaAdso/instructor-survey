<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apprentice;
use App\Models\Course;
use App\Models\Answer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\VerificationCode;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {

    public function showLoginForm() {
        return view('auth.login');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $request->username)
                    ->orWhere('identity_document', $request->username)
                    ->first();

        if (!$user || !$user->is_superuser || !Hash::check($request->password, $user->password)) {

            return back()->withErrors(['error' => 'Credenciales incorrectas o no tienes permisos de administrador.']);
        }

        Auth::guard('admin')->login($user);

        return redirect()->route('admin.dashboard');
    }

    public function loginAreaLeader(Request $request)
    {
        $request->validate([
            'identity_document' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('identity_document', $request->identity_document)
                ->where('is_area_leader', true)
                ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Credenciales incorrectas']);
        }

        Auth::guard('areaLeader')->login($user);
        return redirect()->route('leader.dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'course_code' => 'required|exists:courses,code',
            'identity_document' => 'required',
        ]);

        $course = Course::where('code', $request->course_code)->first();

        // Verificar si la encuesta está cerrada globalmente
        if (!$course->is_survey_open) {
            return response()->json([
                'success' => false,
                'message' => 'La encuesta se encuentra cerrada.',
            ]);
        }

        $apprentice = Apprentice::whereHas('user', function ($query) use ($request) {
                $query->where('identity_document', $request->identity_document);
            })
            ->where('course_id', $course->id)
            ->with('user')
            ->first();

        if (!$apprentice || !$apprentice->user) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incorrectos.',
            ]);
        }

        if (!in_array($apprentice->state, ['En_formacion', 'Etapa_productiva'])) {
            return response()->json([
                'success' => false,
                'message' => 'El aprendiz no está habilitado para realizar la encuesta.',
            ]);
        }

        // Verificar si el estudiante ya realizó la encuesta en los últimos 30 días
        $lastSurvey = Answer::where('apprentice_id', $apprentice->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastSurvey && $lastSurvey->created_at->diffInDays(now()) < 30) {
            $daysRemaining = 30 - $lastSurvey->created_at->diffInDays(now());
            $daysRemaining = intval($daysRemaining);
            return response()->json([
                'success' => false,
                'message' => "Solo puedes realizar la encuesta una vez por trimestre. Podrás responder nuevamente en algunos días si la encuesta se encuentra abierta.",
                // 'message' => "Solo puedes realizar la encuesta una vez al mes. Podrás responder nuevamente en $daysRemaining días si la encuesta se encuentra abierta.",
            ]);
        }

        $email = $apprentice->user->email;
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un email asociado a este aprendiz.',
            ]);
        }

        $code = rand(1000, 9999);
        VerificationCode::updateOrCreate(
            ['apprentice_id' => $apprentice->id],
            ['code' => $code, 'expires_at' => Carbon::now()->addMinutes(5)]
        );

        Mail::to($email)->send(new VerificationMail($code));

        // Devolver una respuesta JSON con el correo
        return response()->json([
            'success' => true,
            'message' => 'Código enviado correctamente.',
            'apprentice_id' => $apprentice->id,
            'email' => $email, // Asegúrate de incluir el correo aquí
        ]);
    }

    public function showVerificationForm($apprenticeId)
    {
        $apprentice = Apprentice::find($apprenticeId);

        if (!$apprentice || !$apprentice->user) {
            return redirect()->route('login')->withErrors(['error' => 'Aprendiz no encontrado.']);
        }

        $email = $apprentice->user->email ?? 'No se encontró email';

        return view('auth.verification', compact('apprenticeId', 'email'));
    }


    public function verifyCode(Request $request)
    {
        $request->validate([
            'apprentice_id' => 'required|exists:apprentices,id',
            'code' => 'required|digits:4',
        ]);

        $verificationCode = VerificationCode::where('apprentice_id', $request->apprentice_id)
            ->where('code', $request->code)
            ->first();

        if (!$verificationCode || !$verificationCode->isValid()) {
            return back()->withErrors(['error' => 'Código incorrecto o ha expirado. Solicita uno nuevo.']);
        }

        $verificationCode->delete();

        $apprentice = Apprentice::find($request->apprentice_id);

        // Usar el guard 'apprentice' para autenticar
        Auth::guard('apprentice')->login($apprentice->user);

        session(['course_id' => $apprentice->course_id]);
        session(['code_verified' => true]);

        return redirect()->route('survey.show', ['apprenticeId' => $apprentice->id, 'surveyId' => 1]);
    }

    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken(); // Solo regenera CSRF

        return redirect()->route('login');
    }

    public function logoutAreaLeader(Request $request)
    {
        Auth::guard('areaLeader')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function logoutApprentice(Request $request)
    {
        Auth::guard('apprentice')->logout();
        session()->forget('code_verified');
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
