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
