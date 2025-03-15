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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #388E3C;
            color: #fff;
            font-size: 1.1rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 16px;
            border: 2px solid #2E7D32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 16px 0;
        }

        .pagination li {
            margin: 0 4px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #38a901;
            transition: background-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #38a901;
            color: white;
            border-color: #38a901;
        }

        .pagination .active span {
            background-color: #38a901;
            color: white;
            border-color: #38a901;
        }

        .pagination .disabled span {
            color: #ccc;
            cursor: not-allowed;
        }

        .Search {
            background-color: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .Search form {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
        }

        .Search form>div {
            flex: 1 1 100%;
            display: flex;
            gap: 16px;
            flex-direction:
        }

        .Search form>div:nth-child(1) {
            justify-content: flex-start;
        }

        .Search form>div:nth-child(2) {
            justify-content: flex-start;
        }

        .Search form>div:nth-child(3) {
            justify-content: flex-end;
        }

        .Search form div>div {
            display: flex;
            flex-direction: row;
            flex: 1;
        }

        .Search form label {
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }

        .Search form input,
        {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        min-width: 100px;
        max-width: 400PX;
        }

        .Search form select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 200px;
            max-width: 400PX;
        }

        .Search form button {
            padding: 10px 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .Search form button:hover {
            background-color: #45a049;
        }

        @media (max-width: 500px) {
            .Search form>div {
                flex-direction: column;
            }
        }

        .search-wrapper {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-start;
        }

        .search-wrapper>div {
            flex: 1;
            min-width: 150px;
        }

        .averages-container {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            gap: 16px;
        }

        .averages-container>div {
            flex: 0 0 100px;
        }

        .small-input {
            min-width: 120px;
            ;
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
                    'knowledge_network_id' => request('knowledge_network_id'),
                ]) }}"
                    class="btn">Descargar PDFs</a>
            </div>
            <a href="{{ route('downloadExcel', [
                'survey_identifier' => request('survey_identifier'),
                'instructor_search' => request('instructor_search'),
                'knowledge_network_id' => request('knowledge_network_id'),
                'min_average' => request('min_average'),
                'max_average' => request('max_average'),
            ]) }}"
                class="btn">
                Descargar Excel
            </a>

        </div>

        <div class="Search">
            <form method="GET" action="{{ route('reportsClose') }}">
                <div class="search-wrapper">
                    <div style="display: contents">
                        <label for="survey_identifier">Formulario:</label>
                        <select name="survey_identifier" id="survey_identifier">
                            <option value="">Todos</option>
                            @foreach ($surveyIdentifiers as $identifier)
                                <option value="{{ $identifier }}" @if ($surveyIdentifier == $identifier) selected @endif>
                                    {{ $identifier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: contents">
                        <label for="instructor_search">Instructor:</label>
                        <input placeholder="Nombre o documento" type="text" name="instructor_search"
                            id="instructor_search" value="{{ request('instructor_search') }}">
                    </div>

                    <div style="display: contents">
                        <label for="knowledge_network_id">Area de conocimiento</label>
                        <input placeholder="Área de conocimiento" type="text" name="knowledge_network_id"
                            id="knowledgeNetworkId" value="{{ request('knowledge_network_id') }}">

                    </div>

                    <div class="averages-container" style="display:contents">
                        <div style="display:contents">
                            <label for="min_average"></label>
                            <input placeholder="Promedio Mínimo" type="number" step="0.01" name="min_average"
                                id="min_average" value="{{ request('min_average') }}" onblur="formatDecimal(this)"
                                class="small-input">
                        </div>
                        <div style="display:contents">
                            <label for="max_average" style="widht:100px"></label>
                            <input placeholder="Promedio Máximo" type="number" step="0.01" name="max_average"
                                id="max_average" value="{{ request('max_average') }}" onblur="formatDecimal(this)"
                                class="small-input">
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content:flex-end">
                    <button type="submit">Filtrar</button>
                    <button type="button" onclick="window.location='{{ route('reportsClose') }}'">Borrar
                        filtros</button>
                </div>
            </form>
        </div>

        <script>
            function formatDecimal(input) {
                if (input.value !== '') {
                    let num = parseFloat(input.value);
                    if (!isNaN(num)) {
                        input.value = num.toFixed(2);
                    }
                }
            }
        </script>

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
                                'knowledge_network_id' => request('knowledge_network_id'),
                            ]) }}"
                                style="color: #2E7D32; font-weight: bold; text-decoration: none;">
                                Descargar PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <ul class="pagination">
                @if ($summaries->onFirstPage())
                    <li class="disabled"><span>Anterior</span></li>
                @else
                    <li><a href="{{ $summaries->previousPageUrl() }}">Anterior</a></li>
                @endif
                @php
                    $currentPage = $summaries->currentPage();
                    $lastPage = $summaries->lastPage();
                    $maxPages = 5;
                    $startPage = max(1, $currentPage - floor($maxPages / 2));
                    $endPage = $startPage + $maxPages - 1;
                    if ($endPage > $lastPage) {
                        $endPage = $lastPage;
                        $startPage = max(1, $endPage - $maxPages + 1);
                    }
                @endphp
                @if ($startPage > 1)
                    <li><a href="{{ $summaries->url(1) }}">1</a></li>
                    @if ($startPage > 2)
                        <li class="disabled"><span>...</span></li>
                    @endif
                @endif
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $summaries->url($page) }}">{{ $page }}</a></li>
                    @endif
                @endfor
                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <li class="disabled"><span>...</span></li>
                    @endif
                    <li><a href="{{ $summaries->url($lastPage) }}">{{ $lastPage }}</a></li>
                @endif
                @if ($summaries->hasMorePages())
                    <li><a href="{{ $summaries->nextPageUrl() }}">Siguiente</a></li>
                @else
                    <li class="disabled"><span>Siguiente</span></li>
                @endif
            </ul>
        </div>
    </div>
    <script>
        document.getElementById('survey_identifier').addEventListener('change', function() {

            var surveyIdentifier = this.value;

            var instructorSelect = document.getElementById('instructor_id');

            if (!instructorSelect) return; // Si no existe, no hace nada.

            var url = "{{ route('api.instructors') }}" + "?survey_identifier=" + encodeURIComponent(

                surveyIdentifier);

            fetch(url)

                .then(response => response.json())

                .then(data => {

                    instructorSelect.innerHTML = '<option value="">Todos</option>';

                    data.forEach(function(inst) {

                        var option = document.createElement('option');

                        option.value = inst.id;

                        option.text = inst.name;

                        instructorSelect.appendChild(option);

                    });

                })

                .catch(error => {

                    console.error("Error al obtener los instructores:", error);

                });

        });
    </script>

</body>



</html>
