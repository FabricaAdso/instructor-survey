<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para la encuesta (ya existentes)
Route::middleware('auth')->get('/survey/{apprenticeId}/{surveyId}', [SurveyController::class, 'showSurvey'])->name('survey.show');


Route::post('/survey/{survey_id}/submit', [SurveyController::class, 'storeAnswers'])->name('survey.submit');