<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\OpenQuestion;
use App\Models\SurveySummary;
use App\Models\TotalReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class TotalReportController extends Controller
{
    public function totalReport(Request $request)
    {
        $surveyIdentifier   = trim($request->input('survey_identifier'));
        $instructorSearch   = trim($request->input('instructor_search'));
        $instructorId       = trim($request->input('instructor_id'));
        $knowledgeNetwork = trim($request->input('knowledge_network_id'));
        $minAverage         = $request->input('min_average');
        $maxAverage         = $request->input('max_average');

        $query = SurveySummary::query()
            ->select(
                'survey_summaries.instructor_id',
                'survey_summaries.survey_identifier',
                DB::raw('AVG(average_qualification) as average_qualification'),
                DB::raw('MAX(total_responses) as total_responses')
            )
            ->join('instructors', 'survey_summaries.instructor_id', '=', 'instructors.id')
            ->join('users', 'instructors.user_id', '=', 'users.id')
            ->addSelect(
                'users.name as instructor_name',
                'users.last_name as instructor_last_name',
                'users.identity_document as identity_document'
            )
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
            ->when($knowledgeNetwork, function ($q) use ($knowledgeNetwork) {
                $q->join('knowledge_networks', 'instructors.knowledge_network_id', '=', 'knowledge_networks.id')
                    ->where('knowledge_networks.name', 'LIKE', "%{$knowledgeNetwork}%");
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

        $summaries = $query->paginate(10)->appends(request()->query());

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


    public function openQuestions(Request $request)
    {

        $surveyIdentifier = $request->input('survey_identifier');
        $instructorId = $request->input('instructor_id');
        $questionId     = $request->input('question_id'); // Parámetro opcional

        $query = OpenQuestion::where('survey_identifier', $surveyIdentifier)
            ->where('instructor_id', $instructorId)
            ->whereNotNull('response');

        // Si se especificó la pregunta, agregar el filtro
        if ($questionId) {
            $query->where('question_id', $questionId);
        }

        $openQuestions = $query->get();

        return response()->json($openQuestions);
    }



    public function totalpdf($id, Request $request)
    {
        $surveyIdentifier   = $request->input('survey_identifier');
        $instructorSearch   = $request->input('instructor_search');
        $knowledgeNetworkId = trim($request->input('knowledge_network_id'));

        $query = SurveySummary::with(['instructor.user', 'question', 'instructor.knowledgeNetwork'])
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
        if ($knowledgeNetworkId) {
            $query->whereHas('instructor.knowledgeNetwork', function ($q) use ($knowledgeNetworkId) {
                $q->where('name', 'LIKE', "%{$knowledgeNetworkId}%");
            });
        }


        $summaries = $query->get();


        if ($summaries->isEmpty()) {
            return redirect()->back()->with('error', 'No se encontraron datos para generar el reporte.');
        }

        $fecha = $summaries->first()->created_at
            ? $summaries->first()->created_at->format('d/m/Y')
            : 'Fecha no disponible';
        $instructor = $summaries->first()->instructor; // Obtenemos el instructor

        $instructorUser = $summaries->first()->instructor->user;
        $identityDocument = optional($instructorUser)->identity_document ?? 'SinIdentidad';

        $data = [
            'summaries'        => $summaries,
            'surveyIdentifier' => $surveyIdentifier,
            'fecha'            => $fecha,
            'instructorName'        => $instructorUser->name,
            'instructorLastName' => $instructorUser->last_name,
            'instructorIdentity' => $instructorUser->identity_document,
            'knowledgeNetworkName' => $instructor->knowledgeNetwork->name ?? 'Área no disponible'
        ];

        $pdf = Pdf::loadView('admin.reports.allPDF', $data);

        $identitySafe = str_replace(['/', '\\'], '', $identityDocument);
        $surveySafe   = str_replace(['/', '\\'], '', $surveyIdentifier);
        //$fechaSafe    = str_replace(['/', '\\'], '-', $fecha); // Cambiar "/" por "-" en la fecha

        return $pdf->download("Reporte_{$identitySafe}_{$surveySafe}.pdf");
    }





    public function downloadAllIndividualPDFs(Request $request)
    {
        $surveyIdentifier   = $request->input('survey_identifier');
        $instructorSearch   = $request->input('instructor_search');
        $instructorId       = $request->input('instructor_id');
        $knowledgeNetworkId = trim($request->input('knowledge_network_id'));

        $query = SurveySummary::with(['instructor.user', 'question', 'instructor.knowledgeNetwork']);

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

        if ($knowledgeNetworkId) {
            $query->whereHas('instructor.knowledgeNetwork', function ($q) use ($knowledgeNetworkId) {
                $q->where('name', 'LIKE', "%{$knowledgeNetworkId}%");
            });
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

        if ($surveyIdentifier && $instructorId) {
            $zipFileName = "Reporte_Encuesta_de_Satisfaccion{$instructorIdentitySafe}_{$surveySafe}_{$today}.zip";
        } elseif ($surveyIdentifier && !$instructorId) {
            $zipFileName = "Reporte_Encuesta_de_Satisfaccion{$surveySafe}_{$today}.zip";
        } elseif (!$surveyIdentifier && $instructorId) {
            $zipFileName = "Reporte_Encuesta_de_Satisfaccion{$instructorIdentitySafe}_{$today}.zip";
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

            $currentInstructor = $firstSummary->instructor;


            $instructorIdentity = optional($firstSummary->instructor->user)->identity_document ?? 'SinIdentidad';
            $data = [
                'surveyIdentifier' => $surveyId,
                'instructor'       => $instructorFull,
                'fecha'            => $fecha,
                'summaries'        => $group,
                'instructorIdentity'     => $instructorIdentity,
                'knowledgeNetworkName' => $currentInstructor->knowledgeNetwork->name ?? 'Área no disponible'
            ];

            $pdf = Pdf::loadView('admin.reports.totalReportPDF', $data);
            $pdfContent = $pdf->output();

            $pdfFileName = "Reporte_{$surveyId}_Instructor_{$instructorFullSafe}.pdf";
            $zip->addFromString($pdfFileName, $pdfContent);
        }

        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }


    public function downloadExcel(Request $request)
    {
        $surveyIdentifier   = $request->input('survey_identifier');
        $instructorSearch   = $request->input('instructor_search');
        $instructorId       = $request->input('instructor_id');
        $knowledgeNetworkId = trim($request->input('knowledge_network_id'));
        $minAverage         = $request->input('min_average');
        $maxAverage         = $request->input('max_average');

        $query = SurveySummary::with(['instructor.user', 'question'])
            ->when($surveyIdentifier, function ($q) use ($surveyIdentifier) {
                $q->where('survey_identifier', $surveyIdentifier);
            })
            ->when($instructorSearch, function ($q) use ($instructorSearch) {
                $q->whereHas('instructor.user', function ($q2) use ($instructorSearch) {
                    $q2->where('name', 'LIKE', "%{$instructorSearch}%")
                        ->orWhere('last_name', 'LIKE', "%{$instructorSearch}%")
                        ->orWhere('identity_document', 'LIKE', "%{$instructorSearch}%");
                });
            })
            ->when($instructorId, function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })
            ->when($knowledgeNetworkId, function ($q) use ($knowledgeNetworkId) {
                $q->whereHas('instructor.knowledgeNetwork', function ($q2) use ($knowledgeNetworkId) {
                    $q2->where('name', 'LIKE', "%{$knowledgeNetworkId}%");
                });
            })
            ->when($minAverage, function ($q, $minAverage) {
                return $q->having('average_qualification', '>=', $minAverage);
            })
            ->when($maxAverage, function ($q, $maxAverage) {
                return $q->having('average_qualification', '<=', $maxAverage);
            });

        $summaries = $query->get();

        $grouped = $summaries->groupBy(function ($item) {
            return $item->survey_identifier . '-' . $item->instructor_id;
        });

        $questionIds = $summaries->pluck('question_id')->unique();
        $questionsList = \App\Models\Question::whereIn('id', $questionIds)
            ->orderBy('id')
            ->get();

        $data = [];

        $header = [
            'Encuesta',
            'Area de conocimiento',
            'Documento',
            'Nombre',
            'Apellido',
            'Calificación Promedio'
        ];
        if ($questionsList->isNotEmpty()) {
            foreach ($questionsList as $question) {
                $header[] = $question->question;
            }
        } else {
            for ($i = 1; $i <= 20; $i++) {
                $header[] = "Pregunta $i";
            }
        }
        $data[] = $header;

        foreach ($grouped as $groupKey => $group) {
            $first = $group->first();
            $surveyId = $first->survey_identifier;
            $knowledgeArea = optional($first->instructor->knowledgeNetwork)->name;
            $document = optional($first->instructor->user)->identity_document;
            $name = optional($first->instructor->user)->name;
            $lastName = optional($first->instructor->user)->last_name;
            $overallAverage = $group->avg('average_qualification');
            $row = [
                $surveyId,
                $knowledgeArea,
                $document,
                $name,
                $lastName,
                number_format($overallAverage, 2)
            ];

            if ($questionsList->isNotEmpty()) {
                foreach ($questionsList as $question) {
                    $summaryForQuestion = $group->firstWhere('question_id', $question->id);
                    $row[] = $summaryForQuestion ? $summaryForQuestion->average_qualification : '';
                }
            } else {
                $sorted = $group->sortBy('question_id')->values();
                for ($i = 0; $i < 20; $i++) {
                    $row[] = isset($sorted[$i]) ? $sorted[$i]->average_qualification : '';
                }
            }
            $data[] = $row;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $boldBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $boldBorderContent = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // $columns = range('A', 'F'); // o el rango que necesites
        // foreach ($columns as $column) {
        //     $sheet->getColumnDimension($column)->setWidth(120);
        // }


        $columnWidths = [
            'A' => 20,
            'B' => 70,
            'C' => 15,
            'D' => 40,
            'E' => 40,
            'F' => 20,
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // Fusionar las celdas A3:E3 y colocar el título en la celda fusionada
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'REPORTE DE ENCUESTA DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA - EJECUCIÓN DE LA FORMACIÓN');

        // Aplicar un estilo opcional al título
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->getStyle('A1:F1')->applyFromArray($boldBorderStyle);

        // Fila 2: Versión
        $sheet->setCellValue('A2', 'Versión:');
        $sheet->mergeCells('B2:F2');
        $sheet->setCellValue('B2', 'v1');

        // Fila 3: Regional
        $sheet->setCellValue('A3', 'Regional:');
        $sheet->mergeCells('B3:F3');
        $sheet->setCellValue('B3', '19 - REGIONAL CAUCA');

        // Fila 4: Centro de Formación
        $sheet->setCellValue('A4', 'Centro de Formación:');
        $sheet->mergeCells('B4:F4');
        $sheet->setCellValue('B4', '9307 - CENTRO DE COMERCIO Y SERVICIOS');

        // Definir que los datos se escriban a partir de la fila 5
        $startRow = 6; // Comienza en la fila 6
        foreach ($data as $rowIndex => $rowData) {
            $colIndex = 1;
            foreach ($rowData as $cellData) {
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . ($rowIndex + $startRow);
                $sheet->setCellValue($cell, $cellData);
                $colIndex++;
            }
        }

        // Obtener la última columna usada en la tabla (según el número de columnas del encabezado)
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($data[0]));

        // Obtener la última fila de la tabla
        $lastRow = count($data) + $startRow - 1;

         // Definir rangos
         $headerRange = "A{$startRow}:{$lastColumn}{$startRow}"; // Solo la fila 6, encabezado
         $contentRange = "A" . ($startRow + 1) . ":{$lastColumn}{$lastRow}"; // Resto de la tabla

         // Aplicar estilos de borde
         $sheet->getStyle($headerRange)->applyFromArray($boldBorderStyle);
         $sheet->getStyle($contentRange)->applyFromArray($boldBorderContent);

         // Aplicar la negrita solo al encabezado
         $sheet->getStyle($headerRange)->getFont()->setBold(true);


        $writer = new Xlsx($spreadsheet);
        if (
            $surveyIdentifier
            && !$instructorSearch
            && !$instructorId
            && !$knowledgeNetworkId
            && !$minAverage
            && !$maxAverage
        ) {
            $fileName = "reporte_encuesta_de_satisfaccion{$surveyIdentifier}.xlsx";
        } else {
            $fileName = 'reporte_encuesta_de_satisfaccion.xlsx';
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }



    public function generateOpenQuestionsPdf(Request $request)
    {
        $surveyIdentifier = $request->input('survey_identifier');
        $instructorId    = $request->input('instructor_id');
        $questionId      = $request->input('question_id'); // Si es necesario

        $query = OpenQuestion::where('survey_identifier', $surveyIdentifier)
            ->where('instructor_id', $instructorId)
            ->whereNotNull('response');

        if ($questionId) {
            $query->where('question_id', $questionId);
        }

        // Cargamos la relación para obtener el texto de la pregunta
        $openQuestions = $query->with('question')->get()->groupBy('question_id')->sortKeys();

        // Si cuentas con un modelo Instructor, lo puedes cargar para los detalles
        $instructor = Instructor::find($instructorId);

        // Ahora enviamos todos los datos necesarios a la vista
        $pdf = Pdf::loadView('admin.reports.test', [
            'openQuestions'   => $openQuestions,
            'surveyIdentifier' => $surveyIdentifier,
            'instructor'      => $instructor
        ]);

        return $pdf->download('openQuestions.pdf');
    }
}
