<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function importUsers(Request $request)
    {
        ini_set('max_execution_time', 300);

        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        try {
            // Guardar el archivo temporalmente en private/temp
            $filePath = $request->file('file')->store('', 'private_temp');
            $fullPath = storage_path("app/private/temp/{$filePath}");

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
            }

            if ($returnVar === 0) {
                // Éxito: El archivo se cargó correctamente
                return response()->json(['message' => 'Archivo importado correctamente', 'output' => implode("\n", $output)], 200);
            } else {
                // Error: El script de Python falló
                return response()->json(['error' => 'Error al importar el archivo', 'output' => implode("\n", $output)], 500);
            }
        } catch (\Exception $e) {
            // Error en el servidor
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

}
