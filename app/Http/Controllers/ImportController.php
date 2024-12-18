<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Instructor;
use App\Models\Apprentice;
use App\Models\Course;
use App\Models\Program;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Exception $e) {
            Log::error('Error al cargar el archivo Excel: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Error al cargar el archivo Excel.']);
        }

        $sheetApprentices = array_slice($spreadsheet->getSheetByName('Aprendiz')->toArray(), 1);
        $sheetInstructors = array_slice($spreadsheet->getSheetByName('Instructores')->toArray(), 1);

        $programs = [];
        foreach ($sheetApprentices as $row) {
            if (!empty($row[0]) && !empty($row[1])) {
                $program = Program::firstOrCreate(
                    ['code' => $row[0]],
                    ['name' => $row[1]]
                );
                $programs[$row[0]] = $program;
            } else {
                Log::warning('Fila de programa vacía o incompleta: ' . json_encode($row));
            }
        }

        $courses = [];
        foreach ($sheetApprentices as $row) {
            if (!empty($row[0]) && !empty($row[6])) {
                $program = $programs[$row[0]] ?? null;
                if ($program) {
                    $course = Course::firstOrCreate(
                        ['code' => $row[6], 'program_id' => $program->id],
                        ['municipality_id' => 1]
                    );
                    $courses[$row[6]] = $course;
                } else {
                    Log::warning('Programa no encontrado para el código: ' . $row[0]);
                }
            } else {
                Log::warning('Fila de curso vacía o incompleta: ' . json_encode($row));
            }
        }

        foreach ($sheetApprentices as $row) {
            if (!empty($row[2]) && !empty($row[3]) && !empty($row[4]) && !empty($row[6])) {
                $course = $courses[$row[6]] ?? null;
                if ($course) {
                    $apprentice = Apprentice::firstOrCreate(
                        ['identity_document' => $row[2]],
                        [
                            'name' => $row[3],
                            'last_name' => $row[4],
                            'second_last_name' => $row[5] ?? null,
                            'course_id' => $course->id,
                        ]
                    );
                } else {
                    Log::warning('Curso no encontrado para el código: ' . $row[6]);
                }
            } else {
                Log::warning('Fila de aprendiz vacía o incompleta: ' . json_encode($row));
            }
        }

        foreach ($sheetInstructors as $row) {
            if (!empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3])) {

                $instructor = Instructor::firstOrCreate(
                    ['identity_document' => $row[2]],
                    [
                        'name' => $row[0],
                        'last_name' => $row[1],
                    ]
                );

                $course = $courses[$row[3]] ?? null;
                if ($course) {
                    $instructor->courses()->syncWithoutDetaching([$course->id]);

                    Log::info('Instructor ' . $instructor->name . ' asociado al curso ' . $course->code);
                } else {
                    Log::warning('Curso no encontrado para la ficha: ' . $row[3]);
                }
            } else {
                Log::warning('Fila de instructor vacía o incompleta: ' . json_encode($row));
            }
        }

        return back()->with('success', 'Datos importados correctamente');
    }
}
