<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tota;
use App\Http\Controllers\TotalReportController;

use function Spatie\LaravelPdf\Support\pdf;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/verify/{apprenticeId}', [AuthController::class, 'showVerificationForm'])->name('verification.form');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verification.verify');

Route::middleware(['auth:apprentice', 'code.verified'])->group(function () {

    Route::get('/survey/{apprenticeId}/{surveyId}', [SurveyController::class, 'showSurvey'])->name('survey.show');
    Route::post('/survey/{apprenticeId}/{surveyId}/submit', [SurveyController::class, 'submitSurvey'])->name('survey.submit');
    Route::get('/survey/complete', [SurveyController::class, 'complete'])->name('survey.complete');

    Route::post('/logout/apprentice', [AuthController::class, 'logoutApprentice'])->name('logout.apprentice');
});

Route::get('login/admin', function() {
    return view('auth.loginAdmin');
})->name('login.admin');

// RUTAS PARA ADMINISTRADORES Y LIDERES
Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin.submit');

Route::middleware(['auth:admin', 'superuser'])->group(function () {

    Route::get('/admin/dashboard', [ReportController::class, 'index'])->name('admin.dashboard');

    // Rutas para reportes generales: colócalas primero para evitar conflictos
    Route::get('reports/general/{instructorId}', [ReportController::class, 'showGeneral'])->name('reportsGeneral');
    Route::get('reports/download/{instructorId}', [ReportController::class, 'showGeneralDownload'])->name('reportsGeneralDownload');

    // Rutas para reportes de cursos (ficha)
    Route::get('/reports/{courseId}/{instructorId}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/downloadcourse/{courseId}/{instructorId}', [ReportController::class, 'reportsDownloadCourse'])->name('reportsDownloadCourse');

    Route::post('/admin/toggle-survey-status', [ReportController::class, 'toggleSurveyStatus'])->name('admin.toggle-survey-status');

    Route::post('/import-users', [ImportController::class, 'importUsers'])->name('import-users');
    Route::post('/import-leaders', [ImportController::class, 'importLeaders'])->name('import-leaders');

    Route::post('/logout/admin', [AuthController::class, 'logoutAdmin'])->name('logout.admin');
    Route::get('/totalReport', [TotalReportController::class, 'totalReport'])->name('reportsClose');
    Route::get('/descargar-pdf/{id}', [TotalReportController::class, 'totalpdf'])->name('totalreport');;
    Route::get('/descargar-todos-pdfs', [TotalReportController::class, 'downloadAllIndividualPDFs'])->name('totalreportpdf.all');
    Route::get('/admin/instructors', [ReportController::class, 'instructorsTable'])->name('admin.instructors');

    Route::get('/api/instructors', [TotalReportController::class, 'getInstructorsBySurveyIdentifier'])->name('api.instructors');
    Route::get('/descargar-excel', [TotalReportController::class, 'downloadExcel'])->name('downloadExcel');

    Route::get('/open-questions', [TotalReportController::class, 'openQuestions'])->name('open.questions');

});



Route::fallback(function () {
    return redirect()->route('login');
});




