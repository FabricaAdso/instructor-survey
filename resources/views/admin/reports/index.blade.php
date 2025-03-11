<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Instructores</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1F2937;
            padding: 10px;
        }

        .container {
            max-width: auto;
            margin: 0 auto;
            padding: 16px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 16px;
            color: #1F2937;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, opacity 0.3s ease;
            font-size: 1rem;
            text-align: center;
            width: auto;
            max-width: 200px;
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .button-group {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 24px;
            justify-content: flex-start;
        }

        .btn-toggle.survey-closed {
            background-color: #4CAF50;
            color: #fff;
            opacity: 1;
        }

        .btn-toggle.survey-open {
            background-color: #4CAF50;
            color: #fff;
            opacity: 0.8;
        }

        .btn-mass {
            background-color: #555;
            color: #fff;
        }

        .cancel-button {
            display: block;
            width: 100%;
            margin-bottom: 8px;
            padding: 8px 16px;
            background-color: #ccc;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cancel-button:hover {
            background-color: #b3b3b3;
        }

        .cancel-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        #searchInput {
            width: 100%;
            max-width: 400px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #388E3C;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            border: 2px solid #2E7D32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa;
        }
    </style>
</head>

<body>
    @include('admin.menu.header')
    <div class="container">
        <h1>Reporte de Instructores</h1>
        <div class="button-group">
            <button id="toggle-survey-status" class="btn btn-toggle {{ $isSurveyOpen ? 'survey-open' : 'survey-closed' }}">
                {{ $isSurveyOpen ? 'Cerrar Encuesta' : 'Abrir Encuesta' }}
            </button>
            <button id="open-modal" class="btn btn-mass">Cargue Masivo</button>
        </div>
        <input type="text" id="searchInput" placeholder="Buscar...">
        <table>
            <thead>
                <tr>
                    <th>N# Documento</th>
                    <th>Nombre</th>
                    <th>Reporte por Fichas</th>
                    <th>Reporte General</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instructors as $instructor)
                    <tr>
                        <td>{{ $instructor->user->identity_document }}</td>
                        <td>{{ $instructor->user->name }} {{ $instructor->user->last_name }}</td>
                        <td style="text-align: center;">
                            <button onclick="openInstructorModal({{ $instructor->id }})" class="btn">
                                Ver Fichas Asociadas
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <button @if (!$instructor->hasGeneralAnswers) disabled @endif onclick="window.location.href='{{ $instructor->hasGeneralAnswers ? route('reportsGeneral', $instructor->id) : '#' }}'" class="btn" style="@if (!$instructor->hasGeneralAnswers) background-color: #D1D5DB; color: #fff; cursor: not-allowed; @endif">
                                Reporte General
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
