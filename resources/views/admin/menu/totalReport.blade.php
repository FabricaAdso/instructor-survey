<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Encuestas Cerradas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Tabla de reporte con filtros y boton de descarga de los datos filtrados">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .tittle-close {
            color: #2E7D32
        }

        body {
            padding: 10px;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1F2937;
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

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        form label {
            font-weight: bold;
            margin-right: 8px;
        }

        form select,
        form input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 50px;
            max-width: 200;
        }

        form button {
            padding: 10px 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            padding: 10px 20px;
            background-color: #388E3C;
            color: #fff;
            font-size: 1.1rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            border: 2px solid #2E7D32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            margin-top: 16px
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
        <div style="display: flex; justify-content: space-between">
            <h2 class="tittle-close">Reporte de Encuestas Cerradas</h2>
            <div>
                <a href="{{ route('totalreportpdf.all', [
                    'survey_identifier' => request('survey_identifier'),
                    'instructor_search' => request('instructor_search'),
                ]) }}"
                    class="btn">Descargar PDFs Filtrados</a>
            </div>
        </div>
        <div class="Search">
            <form method="GET" action="{{ route('reportsClose') }}">
                <div class="search-wrapper">
                    <div>
                        <label for="survey_identifier">Cuestionario:</label>
                        <select name="survey_identifier" id="survey_identifier">
                            <option value="">Todos</option>
                            @foreach ($surveyIdentifiers as $identifier)
                                <option value="{{ $identifier }}" @if ($surveyIdentifier == $identifier) selected @endif>
                                    {{ $identifier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="instructor_search">Instructor:</label>
                        <input placeholder="Nombre o documento" type="text" name="instructor_search"
                            id="instructor_search" value="{{ request('instructor_search') }}">
                    </div>
                </div>
                <div style="display: flex; justify-content:flex-end">
                    <button type="submit">Filtrar</button>
                    <button type="button" onclick="window.location='{{ route('reportsClose') }}'">Borrar
                        filtros</button>
                </div>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Encuesta</th>
                    <th>N° de Documento</th>
                    <th>Nombres y Apellidos</th>
                    <th>Calificación Promedio</th>
                    <th>Total Respuestas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summaries as $summary)
                    <tr>
                        <td>{{ $summary->survey_identifier }}</td>
                        <td>{{ $summary->identity_document }}</td>
                        <td>{{ $summary->instructor_name }} {{ $summary->instructor_last_name }}</td>
                        <td>{{ number_format($summary->average_qualification, 2) }}</td>
                        <td>{{ $summary->total_responses }}</td>
                        <td>
                            <a href="{{ route('totalreport', [
                                'id' => $summary->instructor_id,
                                'survey_identifier' => $summary->survey_identifier,
                                'instructor_search' => request('instructor_search'),
                            ]) }}"
                                style="color: #2E7D32; font-weight: bold; text-decoration: none;">
                                Descargar PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
