<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Definición de las rutas de la API
Route::get('/saludo', function () {
    return response()->json(['mensaje' => 'Hola desde la API']);
});

