<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación
// Rutas públicas
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas con middleware 'auth'
Route::middleware('auth')->group(function () {
    
    // Redirección basada en el rol del usuario
    Route::get('/admin', function () {
        return Auth::user()->role !== 'admin' 
            ? redirect()->route('survey.show', ['apprenticeId' => Auth::user()->id, 'surveyId' => 1])
            : redirect()->route('reports.index');
    })->name('index');

    // Rutas para Reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{courseId}/{instructorId}/{programId}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/general/{instructorId}', [ReportController::class, 'showGeneral'])->name('reports.general');

    // Ruta para Importación
    Route::post('/import', [ImportController::class, 'import'])->name('import');

    // Rutas para Encuestas
    Route::get('/survey/{apprenticeId}/{surveyId}', [SurveyController::class, 'showSurvey'])->name('survey.show');
    Route::post('/survey/{id}/submit', [SurveyController::class, 'submitSurvey'])->name('survey.submit');
    Route::get('/survey/complete', [SurveyController::class, 'complete'])->name('survey.complete');
});
