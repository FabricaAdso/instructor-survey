<?php

namespace App\Http\Controllers;

use App\Models\SurveySummary;
use App\Models\TotalReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;


class TotalReportController extends Controller
{


    public function totalReport(Request $request)
    {
        $surveyIdentifier   = $request->input('survey_identifier');
        $instructorSearch   = $request->input('instructor_search');
        $instructorId       = $request->input('instructor_id');
        $query = SurveySummary::with(['instructor.user', 'course.program']);

        if ($surveyIdentifier) {
            $query->where('survey_identifier', $surveyIdentifier);
        }
        if ($instructorId) {
            $query->where('instructor_id', $instructorId);
        }
        if ($instructorSearch) {
            $query->whereHas('instructor.user', function ($q) use ($instructorSearch) {
                $q->where('name', 'LIKE', "%{$instructorSearch}%")
                    ->orWhere('last_name', 'LIKE', "%{$instructorSearch}%")
                    ->orWhere('identity_document', 'LIKE', "%{$instructorSearch}%");
            });
        }
        $summaries = $query->paginate(10);

        $surveyIdentifiers = SurveySummary::select('survey_identifier')
            ->distinct()
            ->pluck('survey_identifier');

        $instructorsForSelect = collect([]);

        return view('admin.menu.totalReport', compact(
            'summaries',
            'surveyIdentifiers',
            'surveyIdentifier',
            'instructorId',
            'instructorsForSelect',
            'instructorSearch'
        ));
    }


    public function totalpdf($id)
    {
        $survey = SurveySummary::with('instructor.user')->findOrFail($id);

        $instructor = $survey->instructor->user->name ?? 'Instructor no definido';
        $fecha = $survey->created_at ? $survey->created_at->format('d/m/Y') : 'Fecha no disponible';
        $total_respuestas = $survey->total_responses;


        // Genera el PDF a partir de la vista
        $pdf = Pdf::loadView('admin.reports.totalReportPDF', compact('instructor', 'fecha', 'total_respuestas'));

        return $pdf->download('totalReport.pdf');
    }

    public function downloadAllIndividualPDFs(Request $request)
    {
        $surveyIdentifier = $request->input('survey_identifier');
        $instructorSearch = $request->input('instructor_search');
        $instructorId     = $request->input('instructor_id');

        $query = SurveySummary::with('instructor.user');

        if ($surveyIdentifier) {
            $query->where('survey_identifier', $surveyIdentifier);
        }

        if ($instructorSearch) {
            $query->whereHas('instructor.user', function ($q) use ($instructorSearch) {
                $q->where(function ($subQuery) use ($instructorSearch) {
                    $subQuery->where('name', 'LIKE', "%{$instructorSearch}%")
                        ->orWhere('last_name', 'LIKE', "%{$instructorSearch}%")
                        ->orWhere('identity_document', 'LIKE', "%{$instructorSearch}%");
                });
            });
        }

        if ($instructorId) {
            $query->where('instructor_id', $instructorId);
        }
        $summaries = $query->get();

        //   nombre y la ruta para el ZIP
        $zipFileName = 'todos_individuales.pdfs.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['error' => 'No se pudo crear el archivo zip.'], 500);
        }

        foreach ($summaries as $summary) {
            $instructor = $summary->instructor->user->name ?? 'instructor_sin_nombre';
            $fecha = $summary->created_at ? $summary->created_at->format('d/m/Y') : 'fecha_no_disponible';
            $total_respuestas = $summary->total_responses;

            $data = compact('instructor', 'fecha', 'total_respuestas');

            $pdf = Pdf::loadView('admin.reports.totalReportPDF', $data);
            $pdfContent = $pdf->output();

            $pdfFileName = "Reporte_{$summary->id}_{$instructor}.pdf";
            $zip->addFromString($pdfFileName, $pdfContent);
        }

        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
