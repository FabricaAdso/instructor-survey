<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyController;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('import');
});



Route::post('import', [ImportController::class, 'import'])->name('import');

// Rutas de autenticación

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/survey/{apprenticeId}/{surveyId}', [SurveyController::class, 'showSurvey'])->name('survey.show');
    Route::post('survey/{id}/submit', [SurveyController::class, 'submitSurvey'])->name('survey.submit');
    Route::get('/survey/complete', [SurveyController::class, 'complete'])->name('survey.complete');
});
Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/{courseId}/{instructorId}', [ReportController::class, 'show'])->name('reports.show');
Route::get('/courses', [ReportController::class, 'courses'])->name('courses.index');
