<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use function Spatie\LaravelPdf\Support\pdf;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/verify/{apprenticeId}', [AuthController::class, 'showVerificationForm'])->name('verification.form');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verification.verify');

Route::middleware(['auth:apprentice', 'code.verified'])->group(function () {

    Route::get('/survey/{apprenticeId}/{surveyId}', [SurveyController::class, 'showSurvey'])->name('survey.show');
    Route::post('/survey/{id}/submit', [SurveyController::class, 'submitSurvey'])->name('survey.submit');
    Route::get('/survey/complete', [SurveyController::class, 'complete'])->name('survey.complete');

    Route::post('/logout/apprentice', [AuthController::class, 'logoutApprentice'])->name('logout.apprentice');
});

Route::get('login/admin', function() {
    return view('auth.loginAdmin');
})->name('login.admin');

Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin.submit');

Route::middleware(['auth:admin', 'superuser'])->group(function () {

    Route::get('/admin/dashboard', [ReportController::class, 'index'])->name('admin.dashboard');
    // Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{courseId}/{instructorId}/{programId}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/downloadcourse/{courseId}/{instructorId}/{programId}', [ReportController::class, 'reportsDownloadCourse'])->name('reportsDownloadCourse');
    Route::get('reports/general/{instructorId}', [ReportController::class, 'showGeneral'])->name('reportsGeneral');
    Route::get('reports/download/{instructorId}', [ReportController::class, 'showGeneralDownload'])->name('reportsGeneralDownload');

    Route::post('/admin/toggle-survey-status', [ReportController::class, 'toggleSurveyStatus'])->name('admin.toggle-survey-status');

    Route::post('/import-apprentices', [ImportController::class, 'importUsers'])->name('import-apprentices');

    Route::post('/logout/admin', [AuthController::class, 'logoutAdmin'])->name('logout.admin');
});

Route::fallback(function () {
    return redirect()->route('login');
});




