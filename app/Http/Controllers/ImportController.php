<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    // public function import(Request $request)
    // {
    //     set_time_limit(0);

    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     $path = $request->file('file')->getRealPath();

    //     try {
    //         $spreadsheet = IOFactory::load($path);
    //     } catch (\Exception $e) {
    //         Log::error('Error al cargar el archivo Excel: ' . $e->getMessage());
    //         return back()->withErrors(['file' => 'Error al cargar el archivo Excel.']);
    //     }

    //     $sheetApprentices = array_slice($spreadsheet->getSheetByName('Aprendiz')->toArray(), 1);
    //     $sheetInstructors = array_slice($spreadsheet->getSheetByName('Instructores')->toArray(), 1);

    //     $programs = [];
    //     foreach ($sheetApprentices as $row) {
    //         if (!empty($row[0]) && !empty($row[1])) {
    //             $program = Program::firstOrCreate(
    //                 ['code' => $row[0]],
    //                 ['name' => $row[1]]
    //             );
    //             $programs[$row[0]] = $program;
    //         } else {
    //             Log::warning('Fila de programa vacía o incompleta: ' . json_encode($row));
    //         }
    //     }

    //     $courses = [];
    //     foreach ($sheetApprentices as $row) {
    //         if (!empty($row[0]) && !empty($row[6])) {
    //             $program = $programs[$row[0]] ?? null;
    //             if ($program) {
    //                 $course = Course::firstOrCreate(
    //                     ['code' => $row[6], 'program_id' => $program->id],
    //                     ['municipality_id' => 1]
    //                 );
    //                 $courses[$row[6]] = $course;
    //             } else {
    //                 Log::warning('Programa no encontrado para el código: ' . $row[0]);
    //             }
    //         } else {
    //             Log::warning('Fila de curso vacía o incompleta: ' . json_encode($row));
    //         }
    //     }

    //     foreach ($sheetApprentices as $row) {
    //         if (!empty($row[2]) && !empty($row[3]) && !empty($row[4]) && !empty($row[6])) {
    //             $course = $courses[$row[6]] ?? null;
    //             if ($course) {
    //                 $apprentice = Apprentice::firstOrCreate(
    //                     ['identity_document' => $row[2]],
    //                     [
    //                         'name' => $row[3],
    //                         'last_name' => $row[4],
    //                         'second_last_name' => $row[5] ?? null,
    //                         'course_id' => $course->id,
    //                     ]
    //                 );
    //             } else {
    //                 Log::warning('Curso no encontrado para el código: ' . $row[6]);
    //             }
    //         } else {
    //             Log::warning('Fila de aprendiz vacía o incompleta: ' . json_encode($row));
    //         }
    //     }

    //     foreach ($sheetInstructors as $row) {
    //         if (!empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3])) {

    //             $instructor = Instructor::firstOrCreate(
    //                 ['identity_document' => $row[2]],
    //                 [
    //                     'name' => $row[0],
    //                     'last_name' => $row[1],
    //                 ]
    //             );

    //             $course = $courses[$row[3]] ?? null;
    //             if ($course) {
    //                 $instructor->courses()->syncWithoutDetaching([$course->id]);

    //                 Log::info('Instructor ' . $instructor->name . ' asociado al curso ' . $course->code);
    //             } else {
    //                 Log::warning('Curso no encontrado para la ficha: ' . $row[3]);
    //             }
    //         } else {
    //             Log::warning('Fila de instructor vacía o incompleta: ' . json_encode($row));
    //         }
    //     }

    //     return back()->with('success', 'Datos importados correctamente');
    // }

    public function importUsers(Request $request)
    {
        ini_set('max_execution_time', 300);

        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        // Guardar el archivo temporalmente en private/temp
        $filePath = $request->file('file')->store('', 'private_temp');
        $fullPath = storage_path("app/private/temp/{$filePath}");

        // Depuración: Imprimir la ruta del archivo
        echo "Ruta del archivo temporal: {$fullPath}";

        // Ruta al intérprete de Python del entorno virtual
        $pythonPath = base_path('.venv/bin/python3');

        // Ruta al script de Python
        $scriptPath = base_path('scripts/import_users.py');

        // Ejecutar el script de Python
        $output = [];
        $returnVar = 0;
        exec("{$pythonPath} {$scriptPath} {$fullPath} 2>&1", $output, $returnVar);

        // Eliminar el archivo temporal (si existe)
        if (file_exists($fullPath)) {
            unlink($fullPath);
            echo "Archivo temporal eliminado: {$fullPath}";
        } else {
            echo "El archivo no existe: {$fullPath}";
        }

        if ($returnVar === 0) {
            return response()->json(['message' => 'Archivo importado correctamente', 'output' => implode("\n", $output)], 200);
        } else {
            return response()->json(['message' => 'Error al importar el archivo', 'error' => implode("\n", $output)], 500);
        }
    }

}
