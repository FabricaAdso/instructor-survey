<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use function Spatie\LaravelPdf\Support\pdf;

// Rutas de autenticación
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('login/admin', function() {
    return view('auth.loginAdmin');
})->name('login.admin');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




Route::get('login/admin', function() {
    return view('auth.loginAdmin');
})->name('login.admin');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/admin', function () {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('survey.show', ['apprenticeId' => Auth::user()->id, 'surveyId' => 1]);
            }
            return redirect()->route('reports.index');
    })->name('index');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{courseId}/{instructorId}/{programId}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/downloadcourse/{courseId}/{instructorId}/{programId}', [ReportController::class, 'reportsDownloadCourse'])->name('reportsDownloadCourse');
    Route::post('/import', [ImportController::class, 'import'])->name('import');
    Route::get('reports/general/{instructorId}', [ReportController::class, 'showGeneral'])->name('reportsGeneral');
    Route::get('reports/download/{instructorId}', [ReportController::class, 'showGeneralDownload'])->name('reportsGeneralDownload');


    Route::get('/survey/{apprenticeId}/{surveyId}', [SurveyController::class, 'showSurvey'])->name('survey.show');
    Route::post('survey/{id}/submit', [SurveyController::class, 'submitSurvey'])->name('survey.submit');
    Route::get('/survey/complete', [SurveyController::class, 'complete'])->name('survey.complete');
});

Route::fallback(function () {
    return redirect()->route('login');
});




