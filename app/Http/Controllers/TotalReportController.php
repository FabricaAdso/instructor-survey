<?php

namespace App\Http\Controllers;

use App\Models\SurveySummary;
use App\Models\TotalReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ZipArchive;


class TotalReportController extends Controller
{

    public function totalReport(Request $request)
    {
        $surveyIdentifier = trim($request->input('survey_identifier'));
        $instructorSearch = trim($request->input('instructor_search'));
        $instructorId     = trim($request->input('instructor_id'));
        $minAverage       = $request->input('min_average');
        $maxAverage       = $request->input('max_average');

        $query = SurveySummary::query()
            ->select(
                'survey_summaries.instructor_id',
                'survey_summaries.survey_identifier',
                DB::raw('AVG(average_qualification) as average_qualification'),
                DB::raw('MAX(total_responses) as total_responses')
            )
            ->join('instructors', 'survey_summaries.instructor_id', '=', 'instructors.id')
            ->join('users', 'instructors.user_id', '=', 'users.id')
            ->addSelect('users.name as instructor_name', 'users.last_name as instructor_last_name', 'users.identity_document as identity_document')
            ->when($surveyIdentifier, function ($q) use ($surveyIdentifier) {
                $q->where('survey_summaries.survey_identifier', $surveyIdentifier);
            })
            ->when($instructorId, function ($q) use ($instructorId) {
                $q->where('survey_summaries.instructor_id', $instructorId);
            })
            ->when($instructorSearch, function ($q) use ($instructorSearch) {
                $q->where(function ($subQuery) use ($instructorSearch) {
                    $subQuery->where('users.name', 'LIKE', "%{$instructorSearch}%")
                        ->orWhere('users.last_name', 'LIKE', "%{$instructorSearch}%")
                        ->orWhere('users.identity_document', 'LIKE', "%{$instructorSearch}%");
                });
            })
            ->groupBy(
                'survey_summaries.instructor_id',
                'survey_summaries.survey_identifier',
                'users.name',
                'users.last_name',
                'users.identity_document'
            )
            ->when($minAverage, function ($q, $minAverage) {
                return $q->having('average_qualification', '>=', $minAverage);
            })
            ->when($maxAverage, function ($q, $maxAverage) {
                return $q->having('average_qualification', '<=', $maxAverage);
            });

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
            'instructorSearch',
            'minAverage',
            'maxAverage'
        ));
    }


    public function totalpdf($id, Request $request)
    {
        $surveyIdentifier = $request->input('survey_identifier');
        $instructorSearch = $request->input('instructor_search');

        $query = SurveySummary::with(['instructor.user', 'question'])
            ->where('instructor_id', $id);

        if ($surveyIdentifier) {
            $query->where('survey_identifier', $surveyIdentifier);
        }
        if ($instructorSearch) {
            $query->whereHas('instructor.user', function ($q) use ($instructorSearch) {
                $q->where('name', 'LIKE', "%{$instructorSearch}%")
                    ->orWhere('last_name', 'LIKE', "%{$instructorSearch}%")
                    ->orWhere('identity_document', 'LIKE', "%{$instructorSearch}%");
            });
        }

        $summaries = $query->get();

        if ($summaries->isEmpty()) {
            return redirect()->back()->with('error', 'No se encontraron datos para generar el reporte.');
        }

        $fecha = $summaries->first()->created_at
            ? $summaries->first()->created_at->format('d/m/Y')
            : 'Fecha no disponible';

        $instructorUser = $summaries->first()->instructor->user;
        $identityDocument = optional($instructorUser)->identity_document ?? 'SinIdentidad';

        $data = [
            'summaries'        => $summaries,
            'surveyIdentifier' => $surveyIdentifier,
            'fecha'            => $fecha,
        ];

        $pdf = Pdf::loadView('admin.reports.allPDF', $data);

        $identitySafe = str_replace(['/', '\\'], '', $identityDocument);
        $surveySafe   = str_replace(['/', '\\'], '', $surveyIdentifier);
        $fechaSafe    = str_replace(['/', '\\'], '-', $fecha); // Cambiar "/" por "-" en la fecha

        return $pdf->download("Reporte_{$identitySafe}_{$surveySafe}_{$fechaSafe}.pdf");
    }





    public function downloadAllIndividualPDFs(Request $request)
    {
        $surveyIdentifier = $request->input('survey_identifier');
        $instructorSearch = $request->input('instructor_search');
        $instructorId     = $request->input('instructor_id');

        $query = SurveySummary::with(['instructor.user', 'question']);

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

        $groupedSummaries = $summaries->groupBy(function ($item) {
            return $item->survey_identifier . '-' . $item->instructor_id;
        });

        $today = Carbon::now()->format('d-m-Y');

        $surveySafe = $surveyIdentifier ? str_replace(['/', '\\'], '', $surveyIdentifier) : '';

        $instructorIdentitySafe = '';
        if ($instructorId) {
            $firstSummary = $summaries->firstWhere('instructor_id', $instructorId);
            if ($firstSummary && $firstSummary->instructor && $firstSummary->instructor->user) {
                $instructorIdentitySafe = str_replace(['/', '\\'], '', $firstSummary->instructor->user->identity_document);
            }
        }

        // Determinar el nombre del ZIP según los filtros aplicados
        if ($surveyIdentifier && $instructorId) {
            $zipFileName = "Reporte_{$instructorIdentitySafe}_{$surveySafe}_{$today}.zip";
        } elseif ($surveyIdentifier && !$instructorId) {
            $zipFileName = "Reporte_de_Satisfaccion_{$surveySafe}_{$today}.zip";
        } elseif (!$surveyIdentifier && $instructorId) {
            $zipFileName = "Reporte_{$instructorIdentitySafe}_{$today}.zip";
        } else {
            $zipFileName = "Reporte_Encuesta_de_Satisfaccion_Generado_{$today}.zip";
        }

        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['error' => 'No se pudo crear el archivo zip.'], 500);
        }

        foreach ($groupedSummaries as $groupKey => $group) {
            $firstSummary = $group->first();
            $surveyId = $firstSummary->survey_identifier;
            $instructorName = $firstSummary->instructor->user->name ?? 'instructor_sin_nombre';
            $instructorLastName = $firstSummary->instructor->user->last_name ?? '';
            $instructorFull = trim($instructorName . ' ' . $instructorLastName);
            $instructorFullSafe = str_replace(['/', '\\'], '', $instructorFull);
            $fecha = $firstSummary->created_at ? $firstSummary->created_at->format('d/m/Y') : 'fecha_no_disponible';

            $data = [
                'surveyIdentifier' => $surveyId,
                'instructor'       => $instructorFull,
                'fecha'            => $fecha,
                'summaries'        => $group, // Registros para este grupo
            ];

            $pdf = Pdf::loadView('admin.reports.totalReportPDF', $data);
            $pdfContent = $pdf->output();

            $pdfFileName = "Reporte_{$surveyId}_Instructor_{$instructorFullSafe}.pdf";
            $zip->addFromString($pdfFileName, $pdfContent);
        }

        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
