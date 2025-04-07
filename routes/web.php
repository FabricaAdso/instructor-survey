<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LeaderReportController;
use App\Http\Controllers\LeaderTotalReportController;
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

// RUTAS PARA ADMINISTRADORES
Route::get('login/admin', function() {
    return view('auth.loginAdmin');
})->name('login.admin');

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

    Route::get('/totalReport', [TotalReportController::class, 'totalReport'])->name('reportsClose');
    Route::get('/descargar-pdf/{id}', [TotalReportController::class, 'totalpdf'])->name('totalreport');;
    Route::get('/descargar-todos-pdfs', [TotalReportController::class, 'downloadAllIndividualPDFs'])->name('totalreportpdf.all');
    Route::get('/admin/instructors', [ReportController::class, 'instructorsTable'])->name('admin.instructors');

    Route::get('/api/instructors', [TotalReportController::class, 'getInstructorsBySurveyIdentifier'])->name('api.instructors');
    Route::get('/descargar-excel', [TotalReportController::class, 'downloadExcel'])->name('downloadExcel');

    Route::get('/open-questions', [TotalReportController::class, 'openQuestions'])->name('open.questions');
    Route::get('/open-questions-pdf', [TotalReportController::class, 'generateOpenQuestionsPdf'])->name('openQuestionsPdf');

    Route::post('/logout/admin', [AuthController::class, 'logoutAdmin'])->name('logout.admin');
});

// RUTAS PARA LIDERES DE AREA
Route::get('login/areaLeader', function() {
    return view('auth.loginAreaLeader');
})->name('login.areaLeader');

Route::post('login/area/leader', [AuthController::class, 'loginAreaLeader'])->name('login.areaLeader.submit');

Route::middleware(['auth:areaLeader'])->group(function () {

    Route::get('/leader/dashboard', [LeaderReportController::class, 'leaderindex'])->name('leader.dashboard');

    // Rutas para reportes generales: colócalas primero para evitar conflictos
    Route::get('leader/reports/general/{instructorId}', [LeaderReportController::class, 'leadershowGeneral'])->name('leaderreportsGeneral');

    // Rutas para reportes de cursos (ficha)
    Route::get('leader/reports/{courseId}/{instructorId}', [LeaderReportController::class, 'leadershow'])->name('leaderreports.show');

    Route::get('leader/totalReport', [LeaderTotalReportController::class, 'leadertotalReport'])->name('leaderreportsClose');
    Route::get('leader/descargar-pdf/{id}', [LeaderTotalReportController::class, 'leadertotalpdf'])->name('leadertotalreport');
    Route::get('leader/descargar-todos-pdfs', [LeaderTotalReportController::class, 'leaderdownloadAllIndividualPDFs'])->name('leadertotalreportpdf.all');
    Route::get('leader/instructors', [LeaderReportController::class, 'leaderinstructorsTable'])->name('leader.instructors');

    Route::get('leader/api/instructors', [LeaderTotalReportController::class, 'leadergetInstructorsBySurveyIdentifier'])->name('leaderapi.instructors');
    Route::get('leader/descargar-excel', [LeaderTotalReportController::class, 'leaderdownloadExcel'])->name('leaderdownloadExcel');


    Route::post('/logout/leader', [AuthController::class, 'logoutAreaLeader'])->name('logout.areaLeader');

});

Route::fallback(function () {
    return redirect()->route('login');
});




