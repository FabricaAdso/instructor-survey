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
        // Validar que el archivo es un archivo de Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener la ruta del archivo subido
        $path = $request->file('file')->getRealPath();

        // Cargar el archivo Excel
        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Exception $e) {
            Log::error('Error al cargar el archivo Excel: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Error al cargar el archivo Excel.']);
        }

        // Obtener los datos de las hojas y omitir la primera fila (encabezado)
        $sheetApprentices = array_slice($spreadsheet->getSheetByName('Aprendiz')->toArray(), 1);
        $sheetInstructors = array_slice($spreadsheet->getSheetByName('Instructores')->toArray(), 1);

        // Importar Programas
        $programs = [];
        foreach ($sheetApprentices as $row) {
            // Verificar que la fila tenga los datos necesarios
            if (!empty($row[0]) && !empty($row[1])) {
                // Crear o encontrar el programa
                $program = Program::firstOrCreate(
                    ['code' => $row[0]],
                    ['name' => $row[1]]
                );
                $programs[$row[0]] = $program;
            } else {
                Log::warning('Fila de programa vacía o incompleta: ' . json_encode($row));
            }
        }

        // Importar Cursos (Fichas) - Corregir acceso al campo Ficha (columna 7)
        $courses = [];
        foreach ($sheetApprentices as $row) {
            // Verificar que la fila tenga los datos necesarios
            if (!empty($row[0]) && !empty($row[6])) {  // Columna 7 corresponde al índice 6 (0-based)
                $program = $programs[$row[0]] ?? null;
                if ($program) {
                    // Verificar si ya existe el curso con el mismo código y programa
                    $course = Course::firstOrCreate(
                        ['code' => $row[6], 'program_id' => $program->id], // Usar la columna 7 (índice 6)
                        ['municipality_id' => 1] // O el municipio correspondiente
                    );
                    $courses[$row[6]] = $course; // Guardar el curso para su uso posterior
                } else {
                    Log::warning('Programa no encontrado para el código: ' . $row[0]);
                }
            } else {
                Log::warning('Fila de curso vacía o incompleta: ' . json_encode($row));
            }
        }

        // Importar Aprendices
        foreach ($sheetApprentices as $row) {
            // Verificar que la fila tenga todos los datos necesarios
            if (!empty($row[2]) && !empty($row[3]) && !empty($row[4]) && !empty($row[6])) { // Columna 7 es la 6 (índice 6)
                $course = $courses[$row[6]] ?? null;
                if ($course) {
                    // Verificar si el aprendiz ya existe por el documento
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

        // Importar Instructores
        foreach ($sheetInstructors as $row) {
            // Verificar que la fila tenga todos los datos necesarios
            if (!empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3]) && !empty($row[4])) {
                // Verificar si el instructor ya existe por el documento
                $instructor = Instructor::firstOrCreate(
                    ['identity_document' => $row[3]],
                    [
                        'name' => $row[0],
                        'last_name' => $row[1],
                        'second_last_name' => $row[2],
                    ]
                );

                // Buscar el curso relacionado (ficha)
                $course = $courses[$row[4]] ?? null; // Asumimos que la ficha está en la columna 5 (índice 4)
                if ($course) {
                    // Verificar si ya está asociado este instructor a este curso
                    $instructor->courses()->syncWithoutDetaching([$course->id]);

                    // Log para depuración
                    Log::info('Instructor ' . $instructor->name . ' asociado al curso ' . $course->code);
                } else {
                    Log::warning('Curso no encontrado para la ficha: ' . $row[4]);
                }
            } else {
                Log::warning('Fila de instructor vacía o incompleta: ' . json_encode($row));
            }
        }

        // Si llegamos hasta aquí, es que todo salió bien
        return back()->with('success', 'Datos importados correctamente');
    }
}
